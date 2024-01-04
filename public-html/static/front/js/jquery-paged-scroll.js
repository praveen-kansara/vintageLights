/*global console:false */
/*
 * jQuery paged scroll  - different approach for infinite scroll
 *
 * Copyright (c) 2013 Dmitry Mogilko
 * Licensed under the MIT license.
 */

/*
 /* Finish now
 TODO  : horizontal scroll.
/* Finish later
 TODO :  qunit ,simulate scroll - http://stackoverflow.com/questions/6761659/simulate-scroll-event-using-javascript
 TODO :  think about  giving option of calculating trigger on last element of the binder,may be use waypoints plugin.
 TODO  : think about disabling scroll until targetHtml is changed or callback called.

 */
(function ($, window, document, undefined) {
    'use strict';

    /*
     Constructor
     */
    $.ajax_scroll = function (element, options) {

        this.settings = $.extend({},$.ajax_scroll.defaults, options);

        /*
         check if we have everything valid before we start
         */
        this._validate(this.settings);

        /*
         init plugin
        */
        this.timerInterval = -1;
        this.lastDocHeight = 0;
        this.proccesingCallback = false;
        this.lastScrollPosition = 0;
        this.lastHtmlLength = $(this.settings.targetElement).html().length;
        this.instanceID = "paged_scroll" + Math.round(Math.random() * 9999999999);
        var $this = this;

        /*
            create on scroll event handler
         */
        var scrollProcess = (function ($this) {
            return function () {
                if ($this.settings.useScrollOptimization) {
                    if ($this.timerInterval === -1) {
                        $this._debug("Setting timeout:", $this.settings.checkScrollChange);
                        $this.timerInterval = setTimeout(function () {
                            //$this.debug("Running after timeout:");
                            $this._checkScroll(element, $, window, document, $this.settings);
                            $this.timerInterval = -1;

                        }, $this.settings.checkScrollChange);
                    }
                    else {
                        //$this._debug('Ignore this scroll...And saving all the DOM access and calculations');
                    }

                }
                else {
                    $this._checkScroll(element, $, window, document, $this.settings);
                }
            };

        })($this);

        //bind on scroll
        $(element).on('scroll', scrollProcess);
    };

    /*
     Plugin defaults
     */
    $.ajax_scroll.defaults = {

        /*
         required
         your  callback which called which will be called with current page number
         */
        handleScroll:function (page, container, doneCallback) {
            return true;
        },

        /*
         required
         amount of pixels or amount of percent of container (calculated to pixel by plugin) from bottom, to start scroll
         */
        triggerFromBottom:'20%',

        /*
            html to show when loading
        */
        loading : {
              html:'<div title="Loading..." style="margin: 10px auto; width:10%;"><img src="/assets/dist/img/spinner.gif" title="Loading..." alt="Loading..."></div>'

        },

    /*
     required
     element where content will be inserted
     */
        targetElement:null,

        /*
         optional,default is 0
         page number to start with
         */
        startPage:0,

        /*
         optional
         null means infinite scroll
         */
        pagesToScroll:null,

        /*  optional
         before page hook ,if returns false execution stops
         */
        beforePageChanged:function (page, container) {
            return true;
        },

        /*
         optional
         after page scroll callback
         */
        afterPageChanged:function (page, container) {
            return true;
        },

        /*
         optional
         NOT RECOMMENDED to CHANGE!!!
         default : true
         if scroll optimization used ,plugin will not access DOM each time scroll is triggered and will save a lot of overhead,because of not calling callback logic each time
         */
        useScrollOptimization:true,

        /*
         timeout in milliseconds to use in order to check  if scroll change is significant enough to call the "handleScroll" callback
         */
        checkScrollChange:500,

        /*
            frequency to check that target html is checked
        */
        monitorChangeInterval : 300,

        /*
         if monitor target element where finally generated content is inserted

         */
        monitorTargetChange:true,
        /*
         if use debug
         */
        debug:false
    };

    /*
     Use prototype to optimize multiple instances
     */
    $.ajax_scroll.prototype = {

        _calculateStep:function (settings) {
            return (settings.triggerFromBottom.toString().indexOf('%') > -1) ? parseFloat(settings.triggerFromBottom.replace('%', '')) : parseFloat(settings.triggerFromBottom);
        },

        _validate:function (settings) {
            var step = this._calculateStep(settings);
            if (isNaN(step)) {
                throw "Step need to be provided as number or percentage,50 or 5% fro percent for example";
            }
            if (!settings.targetElement || $(settings.targetElement).length === 0) {
                throw "Please provide the selector of target element.(Element where you finally insert the new content)";
            }

        },

        _checkCallbackDone : function(settings,loadingHtml){
            this._debug("Checking target html for change...");
            if (settings.monitorTargetChange && $(settings.targetElement).is(":visible") ) {
                var lastHtmlLength = $(settings.targetElement).html().length;
                if (lastHtmlLength !== this.lastHtmlLength) {
                    this._debug("Html is changed");
                    this.lastHtmlLength = lastHtmlLength;
                    this.proccesingCallback = false;
                    $(loadingHtml).hide();
                }
                else{
                    var $this = this;
                    this._debug("Html is not changed.Check later");
                    setTimeout(function(){$this._checkCallbackDone.call($this,$this.settings,loadingHtml);},$this.settings.monitorChangeInterval);
                }

            }
        },

        _getScrollData:function ($, element, window, document, $this, settings) {
            var elemHeight = parseFloat($(element).height()) , elemScroll = parseFloat($(element).scrollTop()),
                isWindow = (element.self === window) , docHeight = isWindow ? parseFloat($(document).height()) : elemHeight,
                step = docHeight / $this._calculateStep(settings);
            return {elemHeight:elemHeight, elemScroll:elemScroll, isWindow:isWindow, docHeight:docHeight, step:step};
        },

        /*
            plugin logic which check if we need to call the callback
        */
        _checkScroll:function (element, $, window, document, settings) {
            this._debug("Checking scroll on  : " + this.instanceID);
            var $this = this;
            //if element on which content is inserted became not visible don't do exit
            if (settings.targetElement && !$(settings.targetElement).is(":visible")) {
                $this._debug("Ignoring the call because target element is not visible.Exit scroll check ..");
                return;
            }

            /*
                check if callback is still in process
             */
            if ($this.proccesingCallback) {
                $this._debug("Processing callback.Exit...");
                return;
            }


            /*
                get all scroll data in order to understand if we at requested scroll point
            */
            var scrollData = $this._getScrollData($, element, window, document, $this, settings);
            var elemHeight = scrollData.elemHeight;
            var elemScroll = scrollData.elemScroll;
            var isWindow = scrollData.isWindow;
            var docHeight = scrollData.docHeight;
            var step = scrollData.step;

            $this._debug(["Elem height : ", elemHeight, ".Elem scroll :", elemScroll, ".Step is :", step, ".DocHeight :", docHeight, ".Last element height:", $this.lastDocHeight].join(""));
             /*
                calculate  window height + scroll  + step
             */
            var position = isWindow ? elemHeight + elemScroll + step : elemScroll + step;
            $this._debug("Position:" + position + ".Last position:" + $this.lastScrollPosition + ".Last element height:" + $this.lastDocHeight);
            var isPos = (position > $this.lastScrollPosition);
            /*
             understand if we have infinite pages number to scroll and if not, understand we are still not scrolled maximum o page requested.
             */
            var isPageMax = !settings.pagesToScroll || (settings.pagesToScroll && (settings.startPage < settings.pagesToScroll));


            /*
             check that we are at the requested scroll position
             */
            if (position >= docHeight) {
                /*
                 don't handle scrolling back to top and also check if we got to maximum pages to scroll
                 */
                if (isPos && isPageMax) {
                    this.lastScrollPosition = position;
                    this.lastDocHeight = docHeight;
                    settings.startPage = settings.startPage + 1;
                    settings.beforePageChanged(settings.startPage, element);
                    $this._debug("Calling 'handleScroll' callback");
                    $this.proccesingCallback = true;

                    // Functionality for displaying count on pagination.
                    var maxEntries        = $('.maxEntries').html();
                    var maxEntriesPerPage = $('.maxEntriesPerPage').html();

                    if(maxEntries != '' && maxEntries != undefined && maxEntriesPerPage != '' && maxEntriesPerPage != undefined)
                    {
                      var offset = (settings.startPage *  maxEntriesPerPage > maxEntries) ? maxEntries : settings.startPage *  maxEntriesPerPage;
                      var html = '<div title="Loading..." style="margin: 10px auto;" class="text-center"><img src="/assets/dist/img/spinner.gif" title="Loading..." alt="Loading..."> &ensp; Displaying <strong>'+offset+'</strong> of <strong>'+maxEntries+'</strong></div>';
                      var loadingHtml = $(html).appendTo($($this.settings.targetElement));
                    }
                    else
                    {
                      var loadingHtml = $($this.settings.loading.html).appendTo($($this.settings.targetElement));
                    }

                    //var loadingHtml = $($this.settings.loading.html).appendTo($($this.settings.targetElement));
                    $this.lastHtmlLength = $(settings.targetElement).html().length;
                    settings.handleScroll(settings.startPage, element, function () {
                        $this._debug("Callback done.");
                        $this.proccesingCallback = false;
                        loadingHtml.hide();
                    });

                    $this._checkCallbackDone.call($this, $this.settings, loadingHtml);
                    settings.afterPageChanged(settings.startPage, element);


                }

            }
        }, ///check scroll

        /*
         borrowed from  paul irish infinite scroll : hhttps://github.com/paulirish/infinite-scroll - make use of console safe
         */
        _debug:function () {
            try
            {


            if (!this.settings.debug) {
                return;
            }

            if (typeof console !== 'undefined' && typeof console.log === 'function') {
                // Modern browsers
                // Single argument, which is a string
                if ((Array.prototype.slice.call(arguments)).length === 1 && typeof Array.prototype.slice.call(arguments)[0] === 'string') {
                    console.log((Array.prototype.slice.call(arguments)).toString());
                } else {
                    console.log(Array.prototype.slice.call(arguments));
                }
            } else if (!Function.prototype.bind && typeof console !== 'undefined' && typeof console.log === 'object') {
                // IE8
                Function.prototype.call.call(console.log, console, Array.prototype.slice.call(arguments));
            }
        }
        catch(e){

        }


        }



    };

    /*
     create scroll instances
     */
    $.fn.paged_scroll = function (options) {
        return this.each(function () {
            var instance = new $.ajax_scroll(this, options);
            $.data(this, 'jqueryPagedScroll', instance);
        });
    };


}(jQuery, window, document));