
nv.models.scroll = function() {

  //============================================================
  // Public Variables
  //------------------------------------------------------------

  var id,
      margin = {},
      vertical,
      width,
      height,
      minDimension,
      panHandler,
      overflowHandler,
      enable;

  //============================================================

  function scroll(g, gEnter, scrollWrap, xAxis) {

      var defs = g.select('defs'),
          defsEnter = gEnter.select('defs'),
          scrollMask,
          scrollTarget,
          xAxisWrap = scrollWrap.select('.nv-x.nv-axis'),
          barsWrap = scrollWrap.select('.nv-barsWrap'),
          backShadows,
          foreShadows;

      var scrollOffset = 0;

      scroll.init = function(offset, overflow) {

        scrollOffset = offset;
        overflowHandler = overflow;

        this.gradients(enable);
        this.mask(enable);
        this.scrollTarget(enable);
        this.backShadows(enable);
        this.foreShadows(enable);

        this.assignEvents(enable);

        this.resize(enable);
      };

      scroll.pan = function(diff) {
        var distance = 0,
            overflowDistance = 0,
            translate = '',
            x = 0,
            y = 0;

        // don't fire on events other than zoom and drag
        // we need click for handling legend toggle
        if (d3.event) {
          if (d3.event.type === 'zoom' && d3.event.sourceEvent) {
            x = d3.event.sourceEvent.deltaX || 0;
            y = d3.event.sourceEvent.deltaY || 0;
            distance = (Math.abs(x) > Math.abs(y) ? x : y) * -1;
          } else if (d3.event.type === 'drag') {
            x = d3.event.dx || 0;
            y = d3.event.dy || 0;
            distance = vertical ? x : y;
          } else if (d3.event.type !== 'click') {
            return 0;
          }
          overflowDistance = (Math.abs(y) > Math.abs(x) ? y : 0);
        }

        // reset value defined in panMultibar();
        scrollOffset = Math.min(Math.max(scrollOffset + distance, diff), -1);
        translate = 'translate(' + (vertical ? scrollOffset + ',0' : '0,' + scrollOffset) + ')';

        if (scrollOffset + distance > 0 || scrollOffset + distance < diff) {
          overflowHandler(overflowDistance);
        }

        foreShadows
          .attr('transform', translate);
        barsWrap
          .attr('transform', translate);
        xAxisWrap.select('.nv-wrap.nv-axis')
          .attr('transform', translate);

        return scrollOffset;
      };

      scroll.assignEvents = function(enable) {
        if (enable) {

          var zoom = d3.behavior.zoom()
                .on('zoom', panHandler);
          var drag = d3.behavior.drag()
                .origin(function(d) { return d; })
                .on('drag', panHandler);

          scrollWrap
            .call(zoom);
          scrollTarget
            .call(zoom);

          scrollWrap
            .call(drag);
          scrollTarget
            .call(drag);

        } else {

          scrollWrap
              .on("mousedown.zoom", null)
              .on("mousewheel.zoom", null)
              .on("mousemove.zoom", null)
              .on("DOMMouseScroll.zoom", null)
              .on("dblclick.zoom", null)
              .on("touchstart.zoom", null)
              .on("touchmove.zoom", null)
              .on("touchend.zoom", null)
              .on("wheel.zoom", null);
          scrollTarget
              .on("mousedown.zoom", null)
              .on("mousewheel.zoom", null)
              .on("mousemove.zoom", null)
              .on("DOMMouseScroll.zoom", null)
              .on("dblclick.zoom", null)
              .on("touchstart.zoom", null)
              .on("touchmove.zoom", null)
              .on("touchend.zoom", null)
              .on("wheel.zoom", null);

          scrollWrap
              .on("mousedown.drag", null)
              .on("mousewheel.drag", null)
              .on("mousemove.drag", null)
              .on("DOMMouseScroll.drag", null)
              .on("dblclick.drag", null)
              .on("touchstart.drag", null)
              .on("touchmove.drag", null)
              .on("touchend.drag", null)
              .on("wheel.drag", null);
          scrollTarget
              .on("mousedown.drag", null)
              .on("mousewheel.drag", null)
              .on("mousemove.drag", null)
              .on("DOMMouseScroll.drag", null)
              .on("dblclick.drag", null)
              .on("touchstart.drag", null)
              .on("touchmove.drag", null)
              .on("touchend.drag", null)
              .on("wheel.drag", null);
        }
      };

      scroll.resize = function(enable) {

        if (!enable) {
          return;
        }
        var labelOffset = xAxis.labelThickness() + xAxis.tickPadding() / 2,
            v = vertical,
            x = v ? margin.left : labelOffset,
            y = margin.top,
            scrollWidth = width + (v ? 0 : margin[xAxis.orient()] - labelOffset),
            scrollHeight = height + (v ? margin[xAxis.orient()] - labelOffset : 0),
            dim = v ? 'height' : 'width',
            val = v ? scrollHeight : scrollWidth;

        scrollMask
          .attr('x', v ? 2 : -margin.left)
          .attr('y', v ? 0 : 2)
          .attr('width', width + (v ? -2 : margin.left))
          .attr('height', height + (v ? margin.bottom : -2));

        scrollTarget
          .attr('x', x)
          .attr('y', y)
          .attr('width', scrollWidth)
          .attr('height', scrollHeight);

        backShadows.select('.nv-back-shadow-prev')
          .attr('x', x)
          .attr('y', y)
          .attr(dim, val);

        backShadows.select('.nv-back-shadow-more')
          .attr('x', x + (v ? width - 5 : 1))
          .attr('y', y + (v ? 0 : height - 6))
          .attr(dim, val);

        foreShadows.select('.nv-fore-shadow-prev')
          .attr('x', x + (v ? 1 : 0))
          .attr('y', y + (v ? 0 : 1))
          .attr(dim, val);

        foreShadows.select('.nv-fore-shadow-more')
          .attr('x', x + (v ? minDimension - 17 : 0))
          .attr('y', y + (v ? 0 : minDimension - 19))
          .attr(dim, val);
      };

      /* Background gradients */
      scroll.gradients = function(enable) {
        defsEnter
          .append('linearGradient')
          .attr('class', 'nv-scroll-gradient')
          .attr('id', 'nv-back-gradient-prev-' + id);
        var bgpEnter = defsEnter.select('#nv-back-gradient-prev-' + id);

        defsEnter
          .append('linearGradient')
          .attr('class', 'nv-scroll-gradient')
          .attr('id', 'nv-back-gradient-more-' + id);
        var bgmEnter = defsEnter.select('#nv-back-gradient-more-' + id);

        /* Foreground gradients */
        defsEnter
          .append('linearGradient')
          .attr('class', 'nv-scroll-gradient')
          .attr('id', 'nv-fore-gradient-prev-' + id);
        var fgpEnter = defsEnter.select('#nv-fore-gradient-prev-' + id);

        defsEnter
          .append('linearGradient')
          .attr('class', 'nv-scroll-gradient')
          .attr('id', 'nv-fore-gradient-more-' + id);
        var fgmEnter = defsEnter.select('#nv-fore-gradient-more-' + id);

        defs.selectAll('.nv-scroll-gradient')
          .attr('gradientUnits', 'objectBoundingBox')
          .attr('x1', 0)
          .attr('y1', 0)
          .attr('x2', vertical ? 1 : 0)
          .attr('y2', vertical ? 0 : 1);

        bgpEnter
          .append('stop')
          .attr('stop-color', '#000')
          .attr('stop-opacity', '0.3')
          .attr('offset', 0);
        bgpEnter
          .append('stop')
          .attr('stop-color', '#FFF')
          .attr('stop-opacity', '0')
          .attr('offset', 1);
        bgmEnter
          .append('stop')
          .attr('stop-color', '#FFF')
          .attr('stop-opacity', '0')
          .attr('offset', 0);
        bgmEnter
          .append('stop')
          .attr('stop-color', '#000')
          .attr('stop-opacity', '0.3')
          .attr('offset', 1);

        fgpEnter
          .append('stop')
          .attr('stop-color', '#FFF')
          .attr('stop-opacity', '1')
          .attr('offset', 0);
        fgpEnter
          .append('stop')
          .attr('stop-color', '#FFF')
          .attr('stop-opacity', '0')
          .attr('offset', 1);
        fgmEnter
          .append('stop')
          .attr('stop-color', '#FFF')
          .attr('stop-opacity', '0')
          .attr('offset', 0);
        fgmEnter
          .append('stop')
          .attr('stop-color', '#FFF')
          .attr('stop-opacity', '1')
          .attr('offset', 1);
      };

      scroll.mask = function(enable) {
        defsEnter.append('clipPath')
          .attr('class', 'nv-scroll-mask')
          .attr('id', 'nv-edge-clip-' + id)
          .append('rect');

        scrollMask = defs.select('.nv-scroll-mask rect');

        scrollWrap.attr('clip-path', enable ? 'url(#nv-edge-clip-' + id + ')' : '');
      };

      scroll.scrollTarget = function(enable) {
        gEnter.select('.nv-scroll-background')
          .append('rect')
          .attr('class', 'nv-scroll-target')
          //.attr('fill', '#FFF');
          .attr('fill', 'transparent');

        scrollTarget = g.select('.nv-scroll-target');
      };

      /* Background shadow rectangles */
      scroll.backShadows = function(enable) {
        var shadowWrap = gEnter.select('.nv-scroll-background')
              .append('g')
              .attr('class', 'nv-back-shadow-wrap');

        shadowWrap
          .append('rect')
          .attr('class', 'nv-back-shadow-prev');
        shadowWrap
          .append('rect')
          .attr('class', 'nv-back-shadow-more');

        backShadows = g.select('.nv-back-shadow-wrap');

        if (enable) {
          var dimension = vertical ? 'width' : 'height';

          backShadows.select('rect.nv-back-shadow-prev')
            .attr('fill', 'url(#nv-back-gradient-prev-' + id + ')')
            .attr(dimension, 7);

          backShadows.select('rect.nv-back-shadow-more')
            .attr('fill', 'url(#nv-back-gradient-more-' + id + ')')
            .attr(dimension, 7);
        } else {
          backShadows.selectAll('rect').attr('fill', 'transparent');
        }
      };

      /* Foreground shadow rectangles */
      scroll.foreShadows = function(enable) {
        var shadowWrap = gEnter.select('.nv-scroll-background')
              .insert('g')
              .attr('class', 'nv-fore-shadow-wrap');

        shadowWrap
          .append('rect')
          .attr('class', 'nv-fore-shadow-prev');
        shadowWrap
          .append('rect')
          .attr('class', 'nv-fore-shadow-more');

        foreShadows = g.select('.nv-fore-shadow-wrap');

        if (enable) {
          var dimension = vertical ? 'width' : 'height';

          foreShadows.select('rect.nv-fore-shadow-prev')
            .attr('fill', 'url(#nv-fore-gradient-prev-' + id + ')')
            .attr(dimension, 20);

          foreShadows.select('rect.nv-fore-shadow-more')
            .attr('fill', 'url(#nv-fore-gradient-more-' + id + ')')
            .attr(dimension, 20);
        } else {
          foreShadows.selectAll('rect').attr('fill', 'transparent');
        }
      };

    return scroll;
  }


  //============================================================
  // Expose Public Variables
  //------------------------------------------------------------

  scroll.id = function(_) {
    if (!arguments.length) {
      return id;
    }
    id = _;
    return scroll;
  };

  scroll.margin = function(_) {
    if (!arguments.length) {
      return margin;
    }
    margin.top    = typeof _.top    != 'undefined' ? _.top    : margin.top;
    margin.right  = typeof _.right  != 'undefined' ? _.right  : margin.right;
    margin.bottom = typeof _.bottom != 'undefined' ? _.bottom : margin.bottom;
    margin.left   = typeof _.left   != 'undefined' ? _.left   : margin.left;
    return scroll;
  };

  scroll.width = function(_) {
    if (!arguments.length) {
      return width;
    }
    width = _;
    return scroll;
  };

  scroll.height = function(_) {
    if (!arguments.length) {
      return height;
    }
    height = _;
    return scroll;
  };

  scroll.vertical = function(_) {
    if (!arguments.length) {
      return vertical;
    }
    vertical = _;
    return scroll;
  };

  scroll.minDimension = function(_) {
    if (!arguments.length) {
      return minDimension;
    }
    minDimension = _;
    return scroll;
  };

  scroll.panHandler = function(_) {
    if (!arguments.length) {
      return panHandler;
    }
    panHandler = d3.functor(_);
    return scroll;
  };

  scroll.enable = function(_) {
    if (!arguments.length) {
      return enable;
    }
    enable = _;
    return scroll;
  };

  //============================================================

  return scroll;
};
