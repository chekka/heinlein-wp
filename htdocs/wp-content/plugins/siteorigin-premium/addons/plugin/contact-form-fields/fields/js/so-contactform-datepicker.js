/* globals jQuery, pikaday, SiteOriginPremium */

window.SiteOriginPremium = window.SiteOriginPremium || {};

SiteOriginPremium.setupDatepicker = function ( $ ) {

	const generateDateString = ( date, options ) => {
		// Handle text replacement for i18n.
		const weekday = options.i18n.weekdays[ date.getDay() ];
		const day = date.getDate();
		const month = options.i18n.months[ date.getMonth() ];
		const year = date.getFullYear();
		return `${ weekday } ${ day } ${ month } ${ year }`;
	};

	const generateTimeString = ( time ) => {
		let hours = time.getHours();
		let minutes = time.getMinutes();
		const ampm = hours >= 12 ? 'pm' : 'am';
		hours = hours % 12 || 12; // the hour '0' should be '12'.
		minutes = minutes < 10 ? `0${ minutes }` : minutes;
		return `${ hours }:${ minutes } ${ ampm }`;
	};

	$( '.datepicker-container' ).each( function ( index, element ) {
		var $datepickerContainer = $(element);
		var $datepicker = $datepickerContainer.find( '.so-premium-datepicker' );
		var options = $datepicker.data( 'options' );
		var $valInput = $datepickerContainer.siblings( '.so-contactform-datetime' );
		var defaultDate = $valInput.val() ? new Date( $valInput.val() ) : '';

		var updateDate = function () {
			var date = $datepicker.data( 'pikaday' ).getDate();
			var $timepicker = $datepickerContainer.siblings( '.timepicker-container' ).find( '.so-premium-timepicker' );
			if ( $timepicker.length > 0 ) {
				var time = $timepicker.timepicker( 'getTime' );
				if ( time && time instanceof Date && date ) {
					$valInput.val(
						`${ generateDateString( date, options ) } ${ generateTimeString( time ) }`
					);
				}
			} else if ( date ) {
				$valInput.val( generateDateString(date, options ) );
			}
		};
		var yearRange = options.yearRange.split( ',' );
		$datepicker.pikaday( {
			defaultDate: defaultDate,
			yearRange: yearRange,
			minDate: new Date( yearRange[0], 0, 1 ),
			maxDate: new Date( yearRange[1], 11, 31 ),
			bound: options.bound,
			setDefaultDate: true,
			onSelect: updateDate,
			disableWeekends: options.disableWeekends,
			disableDayFn: function( date ) {
				var isDisabledDay = options.disabled.days.indexOf(date.getDay().toString()) > -1;
				if(isDisabledDay) {
					return true;
				}
				return options.disabled.dates.some(function (epoch) {
					var d = new Date(epoch);
					return d.getFullYear() === date.getFullYear() &&
						d.getMonth() === date.getMonth() &&
						d.getDate() === date.getDate();
				});
			},
			isRTL: options.isRTL,
			i18n: options.i18n,
			format: typeof options.date_format === "undefined" ? 'D MMM YYYY' : options.date_format,
			firstDay: options.firstDay,
			toString: function( date, format ) {
				if ( typeof options.date_format !== "undefined" ) {
					return $datepicker.data( 'pikaday' ).getMoment().format( format );
				}

				return generateDateString( date, options );
			},
		} );
		updateDate();
	} );

	$( '.timepicker-container' ).each( function ( index, element ) {
		var $timepickerContainer = $( element );
		var $timepicker = $timepickerContainer.find('.so-premium-timepicker');
		var options = $timepicker.data('options');
		$timepicker.timepicker(options);
		var $valInput = $timepickerContainer.siblings( '.so-contactform-datetime' );
		var defaultTime = $valInput.val() ? new Date( $valInput.val() ) : new Date();

		if ( $timepicker.data( 'prefill' ) ) {
			// If it's not a valid date, assume it's just a time string, e.g. '12:30pm'
			if ( isNaN( defaultTime.valueOf() ) ) {
				$timepicker.val( $valInput.val() );
			} else {
				$timepicker.timepicker( 'setTime', defaultTime );
			}
		}

		var updateTime = function () {
			var $datepicker = $timepickerContainer.siblings( '.datepicker-container' ).find( '.so-premium-datepicker' );
			// If we have a datepicker too, then set the time on the datepicker's selected date.
			if ( $datepicker.length > 0 ) {
				var date = $datepicker.data( 'pikaday' ).getDate();
				if ( date ) {
					var time = $timepicker.timepicker( 'getTime' );
					time = time || defaultTime;
					time = new Date( date.setHours(
						time.getHours(),
						time.getMinutes(),
						time.getSeconds(),
						time.getMilliseconds()
					) );
					$valInput.val(
						`${ generateDateString( date, $datepicker.data( 'options' ) ) } ${ generateTimeString( time ) }`
					);
				}
			} else {
				$valInput.val( $timepicker.val() );
			}
		};

		$timepicker.on( 'changeTime', updateTime );

		updateTime();
	} );

};

jQuery( function( $ ){
	SiteOriginPremium.setupDatepicker( $ );

	if ( window.sowb ) {
		$( window.sowb ).on( 'setup_widgets', function() {
			SiteOriginPremium.setupDatepicker( $ );
		} );
	}
} );
