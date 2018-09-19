<?php
/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */

namespace Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\Listener;

use Doctrine\DBAL\Connection;
use PDO;
use Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\Listener;

/**
 * Updates denormalized data set with the changes made to the original data
 */
final class Updater implements Listener
{
    /**
     * @var Connection
     */
    private $conn;

    /**
     * @var string
     */
    private $table;

    /**
     * Constructor
     *
     * @param Connection $conn
     * @param string $table The name of the table to be updated
     */
    public function __construct(Connection $conn, $table)
    {
        $this->conn = $conn;
        $this->table = $table;
    }

    /**
     * {@inheritDoc}
     *
     * Delete all records with the given user ID.
     */
    public function userDeleted($userId)
    {
        $query = $this->query(
            <<<SQL
DELETE FROM %s WHERE user_id = ?
SQL
        );

        $this->conn->executeUpdate($query, [$userId]);
    }

    /**
     * {@inheritDoc}
     *
     * Same as ::userRemovedFromTeam() but for all users
     */
    public function teamDeleted($teamId)
    {
        // temporarily use two queries instead of one because the previous implementation
        // doesn't use the denormalized table PK during deletion
        $select = $this->query(
            <<<SQL
SELECT tst.team_set_id,
       tm.user_id
  FROM team_sets_teams tst
 INNER JOIN team_memberships tm
    ON tm.team_id = tst.team_id
   AND tm.deleted = 0
  LEFT JOIN (
    SELECT tst.team_set_id,
           tm.user_id,
           tm.team_id
      FROM team_sets_teams tst
INNER JOIN team_memberships tm
        ON tm.team_id = tst.team_id
       AND tm.deleted = 0
     WHERE tm.team_id != ?
) q
    ON q.team_set_id = tst.team_set_id
    AND q.user_id = tm.user_id
 WHERE tm.team_id = ?
   AND q.team_id IS NULL
SQL
        );

        $stmt = $this->conn->executeQuery($select, [
            $teamId,
            $teamId,
        ]);

        $delete = $this->query(
            <<<SQL
DELETE FROM %s
 WHERE team_set_id = ?
   AND user_id = ?
SQL
        );

        while (($row = $stmt->fetch(PDO::FETCH_NUM))) {
            $this->conn->executeUpdate($delete, $row);
        }
    }

    /**
     * {@inheritDoc}
     *
     * For every user which belongs to any of the teams in the team set, create a record with team set ID and user ID
     * ignoring already existing records.
     */
    public function teamSetCreated($teamSetId, array $teamIds)
    {
        $query = $this->query(
            <<<'SQL'
INSERT INTO %1$s (team_set_id, user_id)
SELECT DISTINCT ?,
       user_id
  FROM team_memberships tm
 WHERE team_id IN(?)
   AND deleted = 0
   AND NOT EXISTS (
    SELECT NULL
      FROM %1$s
     WHERE team_set_id = ?
       AND user_id = tm.user_id
)
SQL
        );

        $this->conn->executeUpdate($query, [
            $teamSetId,
            $teamIds,
            $teamSetId,
        ], [
            null,
            Connection::PARAM_STR_ARRAY,
            null,
        ]);
    }

    /**
     * {@inheritDoc}
     *
     * Delete all records with the given team set ID.
     */
    public function teamSetDeleted($teamSetId)
    {
        $query = $this->query(
            <<<SQL
DELETE FROM %s WHERE team_set_id = ?
SQL
        );

        $this->conn->executeUpdate($query, [$teamSetId]);
    }

    /**
     * {@inheritDoc}
     *
     * For every team set which the given user belongs to and the given user,
     * create a record ignoring already existing records.
     */
    public function userAddedToTeam($userId, $teamId)
    {
        $query = $this->query(
            <<<'SQL'
INSERT INTO %1$s (team_set_id, user_id)
SELECT tst.team_set_id,
       ?
  FROM team_sets_teams tst
 WHERE tst.team_id = ?
   AND NOT EXISTS (
    SELECT NULL
      FROM %1$s
     WHERE team_set_id = tst.team_set_id
       AND user_id = ?
)
SQL
        );

        $this->conn->executeUpdate($query, [
            $userId,
            $teamId,
            $userId,
        ]);
    }

    /**
     * {@inheritDoc}
     *
     * For every team set which the given user belongs to only by means of the given team,
     * remove corresponding record.
     */
    public function userRemovedFromTeam($userId, $teamId)
    {
        $query = $this->query(
            <<<SQL
DELETE FROM %s
 WHERE user_id = ?
   AND team_set_id IN (
    SELECT tst.team_set_id
      FROM team_sets_teams tst
INNER JOIN team_memberships tm
        ON tm.team_id = tst.team_id
       AND tm.deleted = 0
 LEFT JOIN (
        SELECT tst.team_set_id,
               tm.team_id
          FROM team_sets_teams tst
    INNER JOIN team_memberships tm
            ON tm.team_id = tst.team_id
           AND tm.deleted = 0
         WHERE tm.user_id = ?
           AND tm.team_id != ?
    ) q
        ON q.team_set_id = tst.team_set_id
     WHERE tm.user_id = ?
       AND tm.team_id = ?
       AND q.team_id IS NULL
    )
SQL
        );

        $this->conn->executeUpdate($query, [
            $userId,
            $userId,
            $teamId,
            $userId,
            $teamId,
        ]);
    }

    /**
     * Builds a query from template by replacing placeholder with the actual table name
     *
     * @param string$template
     * @return string
     */
    private function query($template)
    {
        return sprintf($template, $this->table);
    }

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
        return sprintf('Updater("%s")', $this->table);
    }
}
