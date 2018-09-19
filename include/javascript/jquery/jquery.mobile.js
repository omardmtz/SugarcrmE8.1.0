(function($) {
    // swipe for top nav
    $('#logo').live('touchmove', function (e) {
		e.preventDefault();
	});
	$('#logo').live('swipeRight', function () {
        $('html').find('body').addClass('onL');
	});
	$('#logo').live('swipeLeft', function () {
		$('html').find('body').removeClass('onL');
	})

	$('#create').live('touchmove', function (e) {e.preventDefault();} );
	$('#create').live('swipeLeft', function () {
		$('html').find('body').addClass('onR');
	})  		
	$('#create').live('swipeRight', function () {
		$('html').find('body').removeClass('onR');
	})

	/*$('#moduleList').bind('touchmove', function (e) {e.preventDefault();} );*/
	$('#moduleList').live('swipeLeft', function () {
		$('html').find('body').removeClass('onL');
	})

	$('#createList').live('touchmove', function (e) {e.preventDefault();} );		
	$('#createList').live('swipeRight', function () {
		$('html').find('body').removeClass('onR');
	})  		
	$('#search').live('touchmove', function (e) {e.preventDefault();} );
	$('#search').live('swipeDown', function () {
		$('body').find('#searchForm').toggleClass('hide');
	})
		
    // trigger the module menu - this could be a tap function but zepto will not honor return false
    $('.cube').live('click', function () {
      $('html').find('body').toggleClass('onL');
      return false;
    })

    // trigger the module menu - this could be a tap function but zepto will not honor return false
    $('.launch').live('click', function () {
      $('html').find('body').toggleClass('onR');
      return false;
    })

	$('article').live('swipeLeft',function () {
		  $(this).find('.grip').addClass('on');
      $(this).find('[id^=listing-action] span').removeClass('hide').addClass('on');
	})
		
	$('article').live('swipeRight',function () {
      $(this).find('.grip').removeClass('on');
      $(this).find('[id^=listing-action] span').addClass('hide').removeClass('on');
	})
		
    $('article .grip').live('click', function () {
      $(this).next('.actions').toggleClass('hide');
      $(this).toggleClass('on');
    })

    // search toggle
    $('.navbar').find('#search').live('click', function () {
      $('body').find('#searchForm').toggleClass('hide');
      return false;
    })
    $('#searchForm').find('.cancel').live('click', function () {
      $('body').find('#searchForm').toggleClass('hide');
      return false;
    })

    // fake phone for prototype
    $('#record-action').find('.icon-phone').live('click', function () {
      $('body').append('<div class="over"><h4>Place a call</h4><p><a href="tel:605-334-2345" class="btn btn-large">Home (605)-334-2345</a></p><p><a class="btn btn-large">Mobile (605)-334-2345</a></p><p><a class="btn btn-large">Office (605)-334-2345</a></p><p><a href="" class="btn btn-inverse btn-large" id="cancel">Cancel</a></p></div>');
      return false;
    })
    
    $('.over').find('#cancel').live('click', function () {
      $(this).remove();
      return false;
    })

    $('a[title=Remove]').live('click', function () {
      //$(this).closest('article').hide();
      $(this).closest('article').addClass('deleted').anim({ translateX: window.innerWidth + 'px', opacity: '0'}, .5, 'ease-out');
      setTimeout(rmel,250);
	  return false;
    })   

  	$('.icon-star-empty, .icon-star').live('click', function () {
  	      $(this).toggleClass('icon-star-empty').addClass('icon-star');
  	      return false;
  	})

    $('#tour').live('click', function () {
      $(this).remove();
    })

	var hout = Mustache.render(header_template), fout = Mustache.render(footer_template),
	    items = {
		  "aloop": [
		    { id: "121", name: "Perkin Kleiners", content: "<a href='perkin_kleiners.html'>Perkin Kleiners</a> is a <a href='100seat.html'>100 seat plan</a> of 75K closing in 20 days at <a href=''>quality</a> stage", actions: "<a href='' title='Log'><i class='icon-share icon-md'></i></a> <a href='' title='Remove'><i class='icon-trash icon-md'></i></a>", starred: "icon-star-empty"  },
		    { id: "123", name: "Sabra Khan", content: "<a href='sabra_khan.html'>Sabra Khan</a> has a converted <a href='lead.html'>lead</a> for <a href='weyland.html'>Weyland Corp.</a> for a <a href=''>4 Seat Escape Pod</a>", actions: "<a href='' title='Log'><i class='icon-book icon-md'></i></a> <a href='' title='Task'><i class='icon-check icon-md'></i></a> <a href='' title='<ail'><i class='icon-envelope icon-md'></i></a> <a href='' title='Note'><i class='icon-plus-sign icon-md'></i></a> <a href='' title='Schedule'><i class='icon-calendar icon-md'></i></a> <a href='' title='Remove'><i class='icon-trash icon-md'></i></a>", starred: "icon-star"  },
		    { id: "121", name: "Perkin Kleiners", content: "<a href='perkin_kleiners.html'>Perkin Kleiners</a> is a <a href='100seat.html'>100 seat plan</a> of 75K closing in 20 days at <a href=''>quality</a> stage", actions: "<a href='' title='Log'><i class='icon-share icon-md'></i></a> <a href='' title='Remove'><i class='icon-trash icon-md'></i></a>", starred: "icon-star-empty"  },
		    { id: "121", name: "Perkin Kleiners", content: "<a href='perkin_kleiners.html'>Perkin Kleiners</a> is a <a href='100seat.html'>100 seat plan</a> of 75K closing in 20 days at <a href=''>quality</a> stage", actions: "<a href='' title='Log'><i class='icon-share icon-md'></i></a> <a href='' title='Remove'><i class='icon-trash icon-md'></i></a>", starred: "icon-star-empty"  },
		    { id: "121", name: "Perkin Kleiners", content: "<a href='perkin_kleiners.html'>Perkin Kleiners</a> is a <a href='100seat.html'>100 seat plan</a> of 75K closing in 20 days at <a href=''>quality</a> stage", actions: "<a href='' title='Log'><i class='icon-share icon-md'></i></a> <a href='' title='Remove'><i class='icon-trash icon-md'></i></a>", starred: "icon-star-empty"  },
		    { id: "121", name: "Perkin Kleiners", content: "<a href='perkin_kleiners.html'>Perkin Kleiners</a> is a <a href='100seat.html'>100 seat plan</a> of 75K closing in 20 days at <a href=''>quality</a> stage", actions: "<a href='' title='Log'><i class='icon-share icon-md'></i></a> <a href='' title='Remove'><i class='icon-trash icon-md'></i></a>", starred: "icon-star-empty"  },
		  ]
		},streamout = Mustache.render(stream_items_template,items);

	$('body#nomad').prepend(hout).append(fout).find('#listing').html(streamout)

	function rmel(){
      $('.deleted').remove();
	}

})(window.Zepto);