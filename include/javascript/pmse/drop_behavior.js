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
/*global jCore, MessagePanel, translate, $, AdamFlow, AdamCommandReconnect

*/
/**
 * @class AdamContainerDropBehavior
 * Handle the drop behavior for the new AdamShapes
 *
 *
 * @constructor
 * Create a new Drop Behavior object
 * @param {Array} selectors
 *
 */
var AdamContainerDropBehavior = function (selectors) {
    jCore.ContainerDropBehavior.call(this, selectors);
};
AdamContainerDropBehavior.prototype = new jCore.ContainerDropBehavior();

/**
 * Defines the object type
 * @type {String}
 */
AdamContainerDropBehavior.prototype.type = "AdamContainerDropBehavior";

/**
 * Define the hook method when an shape is dropped and validate if the shape
 * is an boundary event
 * @param shape
 * @param e
 * @param ui
 * @return {Boolean}
 */
AdamContainerDropBehavior.prototype.dropHook = function (shape, e, ui) {
    var id = ui.draggable.attr('id'),
        result,
        droppedElement = shape.canvas.customShapes.find('id', id);
    if (droppedElement.type === 'AdamEvent'
            && droppedElement.getEventType() === 'BOUNDARY') {
        droppedElement.setPosition(droppedElement.oldX, droppedElement.oldY);
        result = false;
    } else {
        result = true;
    }
    return result;
};


/**
 * Define the functionality when an shape is dropped
 * @param shape
 * @return {Function}
 */
AdamContainerDropBehavior.prototype.onDrop = function (shape) {
    return function (e, ui) {


        var customShape,
            canvas = shape.getCanvas(),
            selection,
            sibling,
            i,
            command,
            coordinates,
            id,
            shapesAdded =  [],
            mp,
            containerBehavior = shape.containerBehavior;
        if (canvas.readOnly) {
            return false;
        }

        shape.entered = false;
        if (ui.helper && ui.helper.attr('id') === "drag-helper") {
            return false;
        }
        id = ui.draggable.attr('id');
        customShape = canvas.toolBarShapeFactory(id);
        if (customShape === null) {

            customShape = canvas.customShapes.find('id', id);

            if (!customShape || !shape.dropBehavior.dropHook(shape, e, ui)) {
                return false;
            }

            if (!(customShape.parent &&
                customShape.parent.id === shape.id)) {

                selection = canvas.currentSelection;
                for (i = 0; i < selection.getSize(); i += 1) {
                    sibling = selection.get(i);
                    coordinates = jCore.Utils.getPointRelativeToPage(sibling);
                    coordinates = jCore.Utils
                        .pageCoordinatesToShapeCoordinates(shape, null,
                            coordinates.x, coordinates.y);
                    shapesAdded.push({
                        shape : sibling,
                        container : shape,
                        x : coordinates.x,
                        y : coordinates.y,
                        topLeft : false
                    });
                }
                command = new jCore.CommandSwitchContainer(shapesAdded);
                command.execute();
                canvas.commandStack.add(command);
                canvas.multipleDrop = true;

            }

            // fix resize minWidth and minHeight and also fix the dimension
            // of this shape (if a child made it grow)

            shape.updateDimensions(10);

            canvas.updatedElement = null;
        } else {
            e.pageX = e.pageX || e.originalEvent.pageX;
            e.pageY = e.pageY || e.originalEvent.pageY;

            coordinates = jCore.Utils.pageCoordinatesToShapeCoordinates(shape, e);
            if (!canvas.validatePositions(customShape, coordinates)) {
                mp = new MessagePanel({
                    title: 'Error',
                    wtype: 'Error',
                    message: translate('LBL_PMSE_MESSAGE_CANNOTDROPOUTSIDECANVAS')
                });
                mp.show();
                return false;
            }
            shape.addElement(customShape, coordinates.x, coordinates.y,
                customShape.topLeftOnCreation);
            customShape.attachListeners();

            //since it is a new element in the designer, we triggered the
            //custom on create element event
            canvas.updatedElement = customShape;

            // create the command for this new shape
            command = new jCore.CommandCreate(customShape);
            canvas.commandStack.add(command);
            command.execute();
            //shape.updateSize();
            //console.log('Element Added:',customShape);
            //$('input').blur();
            canvas.hideAllFocusedLabels();


            if (customShape.labels.get(0)) {
                customShape.labels.get(0).getFocus();

               //console.log(customShape.labels.get(0).getID());

                $('#' + customShape.labels.get(0).getID()).find('input').select();


                // $(customShape.labels.get(0).textField.html).focus(
                //     function () {
                //         $(this).select();
                //     }
                // );
                // (function() { // select text on focus
                //     $(this).select();
                // });
                // $(customShape.labels.get(0).textField.html).mouseup(function(e){ // fix for chrome and safari
                //     e.preventDefault();
                // });
            }
        }
    };
};

//

/**
* @class AdamConnectionDropBehavior
* Extends the functionality to handle creation of connections
*
* @constructor
* Creates a new instance of the object
*/
var AdamConnectionDropBehavior = function (selectors) {
    jCore.ConnectionDropBehavior.call(this, selectors);
};
AdamConnectionDropBehavior.prototype = new jCore.ConnectionDropBehavior();
/**
* Defines the object type
* @type {String}
*/
AdamConnectionDropBehavior.prototype.type = "AdamConnectionDropBehavior";

/**
 * Defines a Map of the basic Rules
 * @type {Object}
 */
AdamConnectionDropBehavior.prototype.basicRules = {
    AdamEvent : {
        AdamEvent : {
            connection : 'regular',
            type: 'SEQUENCE'
        },
        AdamActivity : {
            connection : 'regular',
            type: 'SEQUENCE'
        }
    },
    AdamActivity: {
        AdamActivity : {
            connection : 'regular',
            type: 'SEQUENCE'
        },
        AdamArtifact : {
            connection : 'dotted',
            destDecorator: 'con_none',
            type: 'ASSOCIATION'
        },
        AdamIntermediateEvent : {
            connection : 'regular',
            type: 'SEQUENCE'
        },
        AdamEndEvent: {
            connection : 'regular',
            type: 'SEQUENCE'
        },
        AdamGateway : {
            connection : 'regular',
            type: 'SEQUENCE'
        }
    },
    AdamStartEvent : {
        AdamActivity : {
            connection : 'regular',
            type: 'SEQUENCE'
        },
        AdamIntermediateEvent : {
            connection : 'regular',
            type: 'SEQUENCE'
        },
        AdamEndEvent : {
            connection : 'regular',
            type: 'SEQUENCE'
        },
        AdamGateway : {
            connection : 'regular',
            type: 'SEQUENCE'
        }
    },
    AdamIntermediateEvent : {
        AdamActivity : {
            connection : 'regular',
            type: 'SEQUENCE'
        },
        AdamIntermediateEvent : {
            connection : 'regular',
            type: 'SEQUENCE'
        },
        AdamEndEvent : {
            connection : 'regular',
            type: 'SEQUENCE'
        },
        AdamGateway : {
            connection : 'regular',
            type: 'SEQUENCE'
        }
    },
    AdamBoundaryEvent : {
        AdamActivity : {
            connection : 'regular',
            type: 'SEQUENCE'
        },
        AdamIntermediateEvent : {
            connection : 'regular',
            type: 'SEQUENCE'
        },
        AdamEndEvent : {
            connection : 'regular',
            type: 'SEQUENCE'
        },
        AdamGateway : {
            connection : 'regular',
            type: 'SEQUENCE'
        }
    },
    AdamGateway : {
        AdamActivity : {
            connection : 'regular',
            type: 'SEQUENCE'
        },
        AdamIntermediateEvent : {
            connection : 'regular',
            type: 'SEQUENCE'
        },
        AdamEndEvent : {
            connection : 'regular',
            type: 'SEQUENCE'
        },
        AdamGateway : {
            connection : 'regular',
            type: 'SEQUENCE'
        }
    },
    AdamArtifact: {
        AdamActivity: {
            connection : 'dotted',
            destDecorator: 'con_none',
            type: 'ASSOCIATION'
        }
    }
};

/**
 * Defines a Map of the init Rules
 * @type {Object}
 */

AdamConnectionDropBehavior.prototype.initRules = {
    AdamCanvas: {
        AdamCanvas: {
            name: 'AdamCanvas to AdamCanvas',
            rules: AdamConnectionDropBehavior.prototype.basicRules
        }
    },
    AdamActivity: {
        AdamCanvas: {
            name: 'AdamActivity to AdamCanvas',
            rules: AdamConnectionDropBehavior.prototype.basicRules
        }
    }
};


/**
 * Handle the hook functionality when a drop start
 *  @param shape
 */
AdamConnectionDropBehavior.prototype.dropStartHook = function (shape, e, ui) {

    //if (!(ui.helper && ui.helper.attr('id') === "drag-helper")) {
    //  return false;
    //}
    shape.srcDecorator = null;
    shape.destDecorator = null;
    var draggableId = ui.draggable.attr("id"),
        source  = shape.canvas.customShapes.find('id', draggableId),
        prop;
    if (source) {
        prop = this.validate(source, shape);
        if (prop) {
            shape.setConnectionType({
                type: prop.type,
                segmentStyle: prop.connection,
                srcDecorator: prop.srcDecorator,
                destDecorator: prop.destDecorator
            });

            return true;
        } else {
            // verif if port is changed
            if (typeof source !== 'undefined') {
                if (!(ui.helper && ui.helper.attr('id') === "drag-helper")) {
                    return false;
                }
                //showMessage('Invalid Connection');
                shape.setConnectionType('none');
            }
        }
    }

    return true;
};

/**
 * Connection validations method
 * return an object if is valid otherwise return false
 * @param {Connection} source
 * @param {Connection} target
 */
AdamConnectionDropBehavior.prototype.validate = function (source, target) {
    var sType,
        tType,
        rules,
        initRules,
        initRulesName,
        BPMNAuxMap = {
            AdamEvent : {
                'START' : 'AdamStartEvent',
                'END': 'AdamEndEvent',
                'INTERMEDIATE': 'AdamIntermediateEvent',
                'BOUNDARY': 'AdamBoundaryEvent'
            },
            bpmnArtifact : {
                'TEXTANNOTATION': 'bpmnAnnotation'
            }
        };

    if (source && target) {
        if (source.getID() === target.getID()) {
            return false;
        }

        if (this.initRules[source.getParent().getType()]
                && this.initRules[source.getParent().getType()][target.getParent().getType()]) {


            initRules = this.initRules[source.getParent().getType()][target.getParent().getType()].rules;
            initRulesName = this.initRules[source.getParent().getType()][target.getParent().getType()].name;
            // get the types
            sType = source.getType();
            tType = target.getType();
            //Custimize all adam events
            if (sType === 'AdamEvent') {
                if (BPMNAuxMap[sType] && BPMNAuxMap[sType][source.getEventType()]) {
                    sType = BPMNAuxMap[sType][source.getEventType()];
                }
            }
            if (tType === 'AdamEvent') {
                if (BPMNAuxMap[tType] && BPMNAuxMap[tType][target.getEventType()]) {
                    tType = BPMNAuxMap[tType][target.getEventType()];
                }
            }

            if (initRules[sType] && initRules[sType][tType]) {
                rules = initRules[sType][tType];
            } else {
                rules = false;
            }
            if (initRules) {
                switch (initRulesName) {
                case 'bpmnPool to bpmnPool':
                    if (source.getParent().getID() !== target.getParent().getID()) {
                        rules = false;
                    }
                    break;
                case 'bpmnLane to bpmnLane':
                    if (source.getFirstPool(source.parent).getID()
                            !== target.getFirstPool(target.parent).getID()) {
                        if (this.extraRules[sType]
                                && this.extraRules[sType][tType]) {
                            rules = this.extraRules[sType][tType];
                        } else {
                            rules = false;
                        }
                    }
                    break;
                case 'bpmnActivity to bpmnLane':
                    if (this.basicRules[sType]
                            && this.basicRules[sType][tType]) {
                        rules = this.basicRules[sType][tType];
                    } else {
                        rules = false;
                    }
                    break;
                default:
                    break;
                }
            } else {
                rules = false;
            }

            return rules;
        } else {
            // get the types
            sType = source.getType();
            tType = target.getType();
            //
            if (sType === 'AdamEvent') {
                if (BPMNAuxMap[sType] && BPMNAuxMap[sType][source.getEventType()]) {
                    sType = BPMNAuxMap[sType][source.getEventType()];
                }
            }
            if (tType === 'AdamEvent') {
                if (BPMNAuxMap[tType] && BPMNAuxMap[tType][target.getEventType()]) {
                    tType = BPMNAuxMap[tType][target.getEventType()];
                }
            }
            if (this.advancedRules[sType] && this.advancedRules[sType][tType]) {
                rules = this.advancedRules[sType][tType];
            } else {
                rules = false;
            }
            return rules;
        }

    }
};

/**
* Handle the functionality when a shape is dropped
* @param shape
*/
AdamConnectionDropBehavior.prototype.onDrop = function (shape) {
    var that = this;
    return function (e, ui) {
        var canvas  = shape.getCanvas(),
            id = ui.draggable.attr('id'),
            x,
            y,
            currLeft,
            currTop,
            startPoint,
            sourceShape,
            sourcePort,
            endPort,
            endPortXCoord,
            endPortYCoord,
            connection,
            saveCon,
            currentConnection = canvas.currentConnection,
            srcPort,
            dstPort,
            port,
            success = false,
            command,
            aux,
            segmentMap,
            prop;
        shape.entered = false;
        if (!shape.dropBehavior.dropStartHook(shape, e, ui)) {
            return false;
        }
        if (shape.getConnectionType() === "none") {
            App.alert.show('warning_connection', {
                level: 'warning',
                messages: translate('LBL_PMSE_MESSAGE_INVALID_CONNECTION'),
                autoClose: true,
                autoCloseDelay: 9000
            });
            return true;
        }

        if (currentConnection) {
            srcPort = currentConnection.srcPort;
            dstPort = currentConnection.destPort;
            if (srcPort.id === id) {
                port = srcPort;
            } else if (dstPort.id === id) {
                port = dstPort;
            } else {
                port = null;
            }
        }
        if (ui.helper && ui.helper.attr('id') === "drag-helper") {

            //if its the helper then we need to create two ports and draw a
            // connection
            //we get the points and the corresponding shapes involved
            startPoint = shape.canvas.connectionSegment.startPoint;
            sourceShape = shape.canvas.connectionSegment.pointsTo;
            //determine the points where the helper was created
            if (sourceShape.parent && sourceShape.parent.id === shape.id) {
                return true;
            }
            sourceShape.setPosition(sourceShape.oldX, sourceShape.oldY);

            startPoint.x -= sourceShape.absoluteX;
            startPoint.y -= sourceShape.absoluteY;

            //create the ports
            sourcePort = new jCore.Port({
                width: 10,
                height: 10
            });
            endPort = new jCore.Port({
                width: 10,
                height: 10
            });

            //determine the position where the helper was dropped
            endPortXCoord = ui.offset.left - shape.canvas.getX() -
                shape.getAbsoluteX() + shape.canvas.getLeftScroll();
            endPortYCoord = ui.offset.top - shape.canvas.getY() -
                shape.getAbsoluteY() + shape.canvas.getTopScroll();

            // add ports to the corresponding shapes
            // addPort() determines the position of the ports
            sourceShape.addPort(sourcePort, startPoint.x, startPoint.y);
            shape.addPort(endPort, endPortXCoord, endPortYCoord,
                false, sourcePort);

            //add ports to the canvas array for regularShapes
            //shape.canvas.regularShapes.insert(sourcePort).insert(endPort);

            //create the connection
            connection = new AdamFlow({
                srcPort : sourcePort,
                destPort: endPort,
                canvas : shape.canvas,
                segmentStyle: shape.connectionType.segmentStyle,
                flo_type: shape.connectionType.type
            });

            //set its decorators
//            connection.setSrcDecorator(new jCore.ConnectionDecorator({
//                decoratorPrefix: "adam-decorator",
//                decoratorType: "source",
//                width: 11,
//                height: 11,
//                canvas: shape.canvas,
//                parent: connection
//            }));

//            connection.setDestDecorator(new jCore.ConnectionDecorator({
//                decoratorPrefix: "adam-decorator",
//                decoratorType: "target",
//                style: {
//                    cssClasses: ['qennix']
//                },
//                width: 11,
//                height: 11,
//                canvas: shape.canvas,
//                parent: connection
//            }));
//
            connection.setSrcDecorator(new jCore.ConnectionDecorator({
                width: 11,
                height: 11,
                canvas: canvas,
                decoratorPrefix: (typeof shape.connectionType.srcDecorator !== 'undefined'
                    && shape.connectionType.srcDecorator !== null) ?
                        shape.connectionType.srcDecorator : "adam-decorator",
                decoratorType: "source",
                parent: connection
            }));

            connection.setDestDecorator(new jCore.ConnectionDecorator({
                width: 11,
                height: 11,
                canvas: canvas,
                decoratorPrefix: (typeof shape.connectionType.destDecorator !== 'undefined'
                    && shape.connectionType.destDecorator !== null) ?
                        shape.connectionType.destDecorator : "adam-decorator",
                decoratorType: "target",
                parent: connection
            }));

            connection.canvas.commandStack.add(new jCore.CommandConnect(connection));

            //connect the two ports
            connection.connect();
            connection.setSegmentMoveHandlers();

            //add the connection to the canvas, that means insert its html to
            // the DOM and adding it to the connections array
            canvas.addConnection(connection);

            // Filling AdamFlow fields
            connection.setTargetShape(endPort.parent);
            connection.setOriginShape(sourcePort.parent);
            connection.savePoints();

            // now that the connection was drawn try to create the intersections
            //connection.checkAndCreateIntersectionsWithAll();

            //attaching port listeners
            sourcePort.attachListeners(sourcePort);
            endPort.attachListeners(endPort);

            // finally trigger createEvent
            if (canvas.zoomFactor != 1) {
                saveCon = _.extend({}, connection);
                _.each(saveCon.points, function(point) {
                    point.x /= canvas.zoomFactor;
                    point.y /= canvas.zoomFactor;
                });
            } else {
                saveCon = connection;
            }
            canvas.triggerCreateEvent(saveCon, []);
        } else if (port) {
            port.setOldParent(port.getParent());
            port.setOldX(port.getX());
            port.setOldY(port.getY());

            x = ui.position.left;
            y = ui.position.top;
            port.setPosition(x, y);
            shape.dragging = false;
            if (shape.getID() !== port.parent.getID()) {
                port.parent.removePort(port);
                currLeft = ui.offset.left - canvas.getX() -
                    shape.absoluteX + shape.canvas.getLeftScroll();
                currTop = ui.offset.top - canvas.getY() -
                    shape.absoluteY + shape.canvas.getTopScroll();
                shape.addPort(port, currLeft, currTop, true);
                canvas.regularShapes.insert(port);
            } else {
                shape.definePortPosition(port, port.getPoint(true));
            }

            // LOGIC: when portChangeEvent is triggered it gathers the state
            // of the connection but since at this point there's only a segment
            // let's paint the connection, gather the state and then disconnect
            // it (the connection is later repainted on, I don't know how)
            port.connection.connect();
            canvas.triggerPortChangeEvent(port);
            port.connection.disconnect();

            command = new jCore.CommandReconnect(port);
            port.canvas.commandStack.add(command);


            connection = port.getConnection();
            if (connection.srcPort.getID() === port.getID()) {
                prop = AdamConnectionDropBehavior.prototype.validate(
                    shape,
                    connection.destPort.getParent()
                );
            } else {
                prop = AdamConnectionDropBehavior.prototype.validate(
                    connection.srcPort.getParent(),
                    shape
                );
            }

            if (prop) {
                port.setOldParent(port.getParent());
                port.setOldX(port.getX());
                port.setOldY(port.getY());

                x = ui.position.left;
                y = ui.position.top;
                port.setPosition(x, y);
                shape.dragging = false;
                if (shape.getID() !== port.parent.getID()) {
                    port.parent.removePort(port);
                    currLeft = ui.offset.left - canvas.getX() -
                        shape.absoluteX + shape.canvas.getLeftScroll();
                    currTop = ui.offset.top - canvas.getY() - shape.absoluteY +
                        shape.canvas.getTopScroll();
                    shape.addPort(port, currLeft, currTop, true);
                    canvas.regularShapes.insert(port);
                } else {
                    shape.definePortPosition(port, port.getPoint(true));
                }

                // LOGIC: when portChangeEvent is triggered it gathers the state
                // of the connection but since at this point there's only a segment
                // let's paint the connection, gather the state and then disconnect
                // it (the connection is later repainted on, I don't know how)

                aux = {
                    before: {
                        condition: connection.flo_condition,
                        type: connection.flo_type,
                        segmentStyle: connection.segmentStyle,
                        srcDecorator: connection.srcDecorator.getDecoratorPrefix(),
                        destDecorator: connection.destDecorator.getDecoratorPrefix()
                    },
                    after: {
                        type : prop.type,
                        segmentStyle: prop.connection,
                        srcDecorator: prop.srcDecorator,
                        destDecorator: prop.destDecorator
                    }
                };
                connection.connect();
                canvas.triggerPortChangeEvent(port);
                command = new AdamCommandReconnect(port, aux);
                command.execute();
                port.canvas.commandStack.add(command);

            } else {
                return false;
            }
        }
        return false;
    };
};

/**
 * @class AdamConnectionContainerDropBehavior
 * Handle the drop behavior for containers
 *
 * @constructor
 * Create a new instance
 * @param {Object} options
 */
var AdamConnectionContainerDropBehavior  = function (options) {
    AdamConnectionDropBehavior.call(this, options);
};
AdamConnectionContainerDropBehavior.prototype = new AdamConnectionDropBehavior();
/**
 * Defines the object type
 * @type {String}
 */
AdamConnectionContainerDropBehavior.prototype.type = "AdamConnectionContainerDropBehavior";


AdamConnectionContainerDropBehavior.prototype.defaultSelector =
    ".custom_shape,.port";
//AdamConnectionContainerDropBehavior.prototype.validRelations = AdamContainerDropBehavior.prototype.validRelations;
/**
 * Extends the drap functionality
 * @param {Object} shape
 * @return {Function}
 */
AdamConnectionContainerDropBehavior.prototype.onDrop = function (shape) {
    return function (e, ui) {
        if (!AdamConnectionDropBehavior.prototype.onDrop.call(this, shape)(e, ui)) {
            AdamContainerDropBehavior.prototype.onDrop.call(this, shape)(e, ui);
        }
    };
};
AdamConnectionContainerDropBehavior.prototype.getSpecificType = AdamContainerDropBehavior.prototype.getSpecificType;

AdamConnectionContainerDropBehavior.prototype.validDrop = AdamContainerDropBehavior.prototype.validDrop;
AdamConnectionContainerDropBehavior.prototype.dropHook = AdamContainerDropBehavior.prototype.dropHook;
