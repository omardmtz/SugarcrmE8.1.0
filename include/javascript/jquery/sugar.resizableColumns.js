/*
 * Modifications made by SugarCRM Inc. © 2014. These modifications are subject
 * to the same terms as the MIT license.
 * For information on the full license terms or related notices, go to
 * http://www.sugarcrm.com/third-party-software.
 */
/* jQuery Resizable Columns v0.1.0 | http://dobtco.github.io/jquery-resizable-columns/ | Licensed MIT | Built Fri Oct 24 2014 18:33:37 */
var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; },
    __slice = [].slice;

(function($, window) {
    var ResizableColumns, pointerX, stripPx, _parseWidth, _setWidth;
    var uniqueIds = 0;
    stripPx = function(width) {
        if (!width) {
            return 0;
        } else {
            if (typeof width === 'string') {
                return width.replace('px', '');
            } else {
                return width;
            }
        }
    };
    _parseWidth = function(_usePixels, node) {
        return parseFloat((node.offsetWidth+'').replace((_usePixels ? 'px' : '%'), ''));
    };
    _setWidth = function(_usePixels, node, width) {
        if (_usePixels) {
            width = width.toFixed(2);
            width = width + 'px';
            node.style.minWidth = width;
            node.style.width = width;
            node.style.maxWidth = width;
        } else {
            width = width.toFixed(2);
            width = "" + width + "%";
            node.style.width = width;
        }
        return width;
    };
    pointerX = function(e) {
        if (e.type.indexOf('touch') === 0) {
            return (e.originalEvent.touches[0] || e.originalEvent.changedTouches[0]).pageX;
        }
        return e.pageX;
    };
    ResizableColumns = (function() {
        ResizableColumns.prototype.defaults = {
            usePixels: false,
            selector: 'tr th:visible',
            store: window.store,
            syncHandlers: true,
            resizeFromBody: true,
            maxWidth: null,
            minWidth: null
        };

        function ResizableColumns($table, options) {
            this.pointerdown = __bind(this.pointerdown, this);
            this.constrainWidth = __bind(this.constrainWidth, this);
            var usePixels;
            this.options = $.extend({}, this.defaults, options);
            this.$table = $table;
            usePixels = this.options.usePixels;
            this.parseWidth = function(node) {
                return _parseWidth(usePixels, node);
            };
            this.setWidth = function(node, width) {
                return _setWidth(usePixels, node, width);
            };
            this.setHeaders();
            if (this.options.store != null) {
                this.restoreColumnWidths();
            }
            this.syncHandleWidths();

            this._uniqueId = ++uniqueIds;

            $(window).on('resize.rc' + this._uniqueId, ((function(_this) {
                return function() {
                    var sync = _this.syncHandleWidths();
                    var minTableWidth = _this.options.getMinTableWidth && _this.options.getMinTableWidth();
                    if (minTableWidth) {
                        _this._minTableWidth = minTableWidth;
                    }
                    if (_this.options.restore) {
                        _this.options.restore();
                    }
                    _this._currentTableWidth = _this.$table.width();
                    return sync;
                };
            })(this)));

            if (this.options.start) {
                this.$table.bind('column:resize:start.rc', this.options.start);
            }
            if (this.options.resize) {
                this.$table.bind('column:resize.rc', this.options.resize);
            }
            if (this.options.stop) {
                this.$table.bind('column:resize:stop.rc', this.options.stop);
            }
            if (this.options.restore) {
                this.$table.bind('column:resize:restore.rc', this.options.restore);
            }
        }

        ResizableColumns.prototype.triggerEvent = function(type, args, original) {
            var event;
            event = $.Event(type);
            event.originalEvent = $.extend({}, original);
            return this.$table.trigger(event, [this].concat(args || []));
        };

        ResizableColumns.prototype.getColumnId = function($el) {
            return this.$table.data('resizable-columns-id') + '-' + $el.data('resizable-column-id');
        };

        ResizableColumns.prototype.setHeaders = function() {
            this._leftoverWidth = this.$table.width();
            this.$tableHeaders = this.$table.find(this.options.selector);
            var i = this.$tableHeaders.length;
            var width;
            while (i) {
                i--;
                width = $(this.$tableHeaders[i]).outerWidth();
                this._leftoverWidth -= width;
            }
            if (this.options.usePixels) {
                this.assignPixelWidths();
            } else {
                this.assignPercentageWidths();
            }
            return this.createHandles();
        };

        ResizableColumns.prototype.destroy = function() {
            $(window).off('resize.rc' + this._uniqueId);
            this.$handleContainer.remove();
            this.$table.removeData('resizableColumns');
        };

        ResizableColumns.prototype.assignPercentageWidths = function() {
            return this.$tableHeaders.each((function(_this) {
                return function(_, el) {
                    var $el;
                    $el = $(el);
                    return _this.setWidth($el[0], $el.outerWidth() / _this.$table.width() * 100);
                };
            })(this));
        };

        ResizableColumns.prototype.assignPixelWidths = function() {
            var columns = [];
            var self = this;
            this.$tableHeaders.each((function(_this) {
                return function(_, el) {
                    var $el;
                    $el = $(el);
                    columns.push($el.outerWidth() - stripPx($el.css('paddingLeft') - stripPx($el.css('paddingRight'))));
                };
            })(this));
            return this.$tableHeaders.each(function(i, el) {
                return self.setWidth(el, columns[i]);
            });
        };

        ResizableColumns.prototype.createHandles = function() {
            var _ref;
            if ((_ref = this.$handleContainer) != null) {
                _ref.remove();
            }
            this.$table.before((this.$handleContainer = $("<div class='rc-handle-container' />")));
            this.$tableHeaders.each((function(_this) {
                return function(i, el) {
                    var $handle;
                    if (_this.$tableHeaders.eq(i + 1).length === 0 || (_this.$tableHeaders.eq(i).attr('data-noresize') != null) || (_this.$tableHeaders.eq(i + 1).attr('data-noresize') != null)) {
                        return;
                    }
                    $handle = $("<div class='rc-handle' />");
                    $handle.data('th', $(el));
                    return $handle.appendTo(_this.$handleContainer);
                };
            })(this));
            return this.$handleContainer.on('mousedown touchstart', '.rc-handle', this.pointerdown);
        };

        ResizableColumns.prototype.syncHandleWidths = function() {
            if (this.options.usePixels) {
                return this.syncHandleWidthsPx();
            }
            return this.$handleContainer.width(this.$table.width()).find('.rc-handle').each((function(_this) {
                return function(_, el) {
                    var $el;
                    $el = $(el);
                    return $el.css({
                        left: $el.data('th').outerWidth() + ($el.data('th').offset().left - _this.$handleContainer.offset().left),
                        height: _this.options.resizeFromBody ? _this.$table.height() : _this.$table.find('thead').height()
                    });
                };
            })(this));
        };

        ResizableColumns.prototype.saveColumnWidths = function() {
            var columns;
            columns = [];
            this.$tableHeaders.each((function(_this) {
                return function(_, el) {
                    var $el, width;
                    $el = $(el);
                    if ($el.attr('data-noresize') == null) {
                        width = _this.parseWidth($el[0]);
                        columns.push(width);
                        if (_this.options.store != null) {
                            return _this.options.store.set(_this.getColumnId($el), width);
                        }
                    }
                };
            })(this));
            return this.$table.trigger('column:resize:save', [columns]);
        };

        ResizableColumns.prototype.restoreColumnWidths = function() {
            return this.$tableHeaders.each((function(_this) {
                return function(_, el) {
                    var $el, width;
                    $el = $(el);
                    if ((_this.options.store != null) && (width = _this.options.store.get(_this.getColumnId($el)))) {
                        return _this.setWidth($el[0], width);
                    }
                };
            })(this));
        };

        ResizableColumns.prototype.totalColumnWidths = function() {
            var total;
            if (this.options.usePixels) {
                return this.totalColumnWidthsPx();
            }
            total = 0;
            this.$tableHeaders.each((function(_this) {
                return function(_, el) {
                    return total += parseFloat($(el)[0].style.width.replace('%', ''));
                };
            })(this));
            return total;
        };

        ResizableColumns.prototype.syncHandleWidthsPx = function() {
            return this.$handleContainer.css('minWidth', '100%').find('.rc-handle').each((function(_this) {
                return function(_, el) {
                    var $el, height, left;
                    $el = $(el);
                    left = $el.data('th').innerWidth();
                    left -= stripPx($el.css('paddingLeft'));
                    left -= stripPx($el.css('paddingRight'));
                    left += $el.data('th').offset().left;
                    left -= _this.$handleContainer.offset().left;
                    height = _this.options.resizeFromBody ? _this.$table.height() : _this.$table.find('thead').height();
                    return $el.css({
                        left: left,
                        height: height
                    });
                };
            })(this));
        };

        ResizableColumns.prototype.totalColumnWidthsPx = function() {
            var total;
            total = 0;
            this.$table.each((function(_this) {
                return function(_, el) {
                    var $el;
                    $el = $(el);
                    return total += parseFloat(stripPx($el.outerWidth()));
                };
            })(this));
            return total;
        };

        ResizableColumns.prototype.constrainWidth = function(width) {
            if (this.options.minWidth != null) {
                width = Math.max(this.options.minWidth, width);
            }
            if (this.options.maxWidth != null) {
                width = Math.min(this.options.maxWidth, width);
            }
            return width;
        };

        ResizableColumns.prototype.pointerdown = function(e) {
            var $currentGrip, $leftColumn, $ownerDocument, $rightColumn, isLtr, newWidths, startPosition, widths;
            e.preventDefault();
            isLtr = this.$table.css('direction') !== 'rtl';
            $ownerDocument = $(e.currentTarget.ownerDocument);
            startPosition = pointerX(e);
            $currentGrip = $(e.currentTarget);
            if (isLtr) {
                $leftColumn = $currentGrip.data('th');
                $rightColumn = this.$tableHeaders.eq(this.$tableHeaders.index($leftColumn) + 1);
            } else {
                $rightColumn = $currentGrip.data('th');
                $leftColumn = this.$tableHeaders.eq(this.$tableHeaders.index($rightColumn) - 1);
            }
            widths = {
                left: this.parseWidth($leftColumn[0]),
                right: this.parseWidth($rightColumn[0])
            };
            newWidths = {
                left: widths.left,
                right: widths.right
            };
            this.$handleContainer.add(this.$table).addClass('rc-table-resizing');
            $leftColumn.add($rightColumn).add($currentGrip).addClass('rc-column-resizing');
            this.triggerEvent('column:resize:start', [$leftColumn, $rightColumn, newWidths.left, newWidths.right], e);
            $ownerDocument.on('mousemove.rc touchmove.rc', (function(_this) {
                return function(e) {
                    var difference;
                    var localWidths = {
                        left: newWidths.left,
                        right: newWidths.right
                    };
                    difference = pointerX(e) - startPosition;
                    startPosition = pointerX(e);
                    if (!isLtr) {
                        difference = -difference;
                    }
                    if (!_this.options.usePixels) {
                        difference = difference / _this.$table.width() * 100;
                    }
                    if (_this.options.usePixels) {
                        _this._currentTableWidth = _this.$table.width() + difference;
                        var shouldRestrict = _this._minTableWidth && (_this._currentTableWidth < _this._minTableWidth);
                        if (shouldRestrict) {
                            _this.setWidth($rightColumn[0], newWidths.right = _this.constrainWidth(localWidths.right - difference));
                        }
                        _this.setWidth($leftColumn[0], newWidths.left = _this.constrainWidth(localWidths.left + difference));
                    } else {
                        _this.setWidth($leftColumn[0], newWidths.left = _this.constrainWidth(widths.left + difference));
                        _this.setWidth($rightColumn[0], newWidths.right = _this.constrainWidth(widths.right - difference));
                    }
                    if (_this.options.syncHandlers != null) {
                        _this.syncHandleWidths();
                    }
                    return _this.triggerEvent('column:resize', [$leftColumn, $rightColumn, newWidths.left, newWidths.right], e);
                };
            })(this));
            return $ownerDocument.one('mouseup touchend', (function(_this) {
                return function() {
                    $ownerDocument.off('mousemove.rc touchmove.rc');
                    _this.$handleContainer.add(_this.$table).removeClass('rc-table-resizing');
                    $leftColumn.add($rightColumn).add($currentGrip).removeClass('rc-column-resizing');
                    _this.syncHandleWidths();
                    _this.saveColumnWidths();
                    return _this.triggerEvent('column:resize:stop', [$leftColumn, $rightColumn, newWidths.left, newWidths.right], e);
                };
            })(this));
        };

        return ResizableColumns;

    })();
    return $.fn.extend({
        resizableColumns: function() {
            var args, option;
            option = arguments[0], args = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
            return this.each(function() {
                var $table, data;
                $table = $(this);
                data = $table.data('resizableColumns');
                if (!data) {
                    $table.data('resizableColumns', (data = new ResizableColumns($table, option)));
                }
                if (typeof option === 'string') {
                    return data[option].apply(data, args);
                }
            });
        }
    });
})(window.jQuery, window);
