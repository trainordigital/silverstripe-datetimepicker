(function ($) {
  $.entwine('ss', function ($) {

    $('.datetimepicker-date-field').entwine({
      onmatch: function () {
        var $valueField = this.closest('.form__field-holder').find('.datetimepicker-date-value')
        var $clearButton = this.closest('.form__field-holder').find('.datetimepicker-clear')
        var selectedDate = moment($valueField.val(), 'YYYY-MM-DD')
        var minDate = moment(this.data('min'), 'YYYY-MM-DD')
        var maxDate = moment(this.data('max'), 'YYYY-MM-DD')

        var datepicker = new Pikaday({
          field: this.get(0),
          format: 'MMM D, YYYY',
          minDate: minDate.isValid() ? minDate.toDate() : null,
          maxDate: maxDate.isValid() ? maxDate.toDate() : null
        })

        if (selectedDate.isValid()) {
          datepicker.setDate(selectedDate.toDate(), true)
        }

        $clearButton.on('click', function () {
          datepicker.setDate(null, true)
        })

        this.data('datepicker', datepicker)
      },
      onchange: function () {
        var $valueField = this.closest('.form__field-holder').find('.datetimepicker-date-value')
        var datepicker = this.data('datepicker')
        var date = moment(this.val(), 'MMM D, YYYY')

        if (date.isValid()) {
          $valueField.val(date.format('YYYY-MM-DD')).trigger('change')
        }
        else {
          if (datepicker.getDate() !== null) {
            datepicker.setDate(null, true)
          }

          $valueField.val(null).trigger('change')
        }
      },
      onunmatch: function () {
      }
    })

    $('.form-group.daterangepicker').entwine({
      onmatch: function () {
        this.find('.form__fieldgroup.form__field-holder').datepair({
          anchor: null,
          dateClass: 'datetimepicker-date-field',
          parseDate: function (input) {
            return input.value && $(input).data('datepicker').getDate()
          },
          updateDate: function (input, dateObj) {
            $(input).data('datepicker').setDate(dateObj, true)
          },
          parseTime: null,
          updateTime: null,
          setMinTime: null
        })
      }
    })

    $('.datetimepicker-time-field').entwine({
      onmatch: function () {
        var $valueField = this.closest('.form__field-holder').find('.datetimepicker-time-value')
        var $clearButton = this.closest('.form__field-holder').find('.datetimepicker-clear')
        var selectedTime = moment($valueField.val(), 'HH:mm:ss')
        var self = this

        this.timepicker({
          timeFormat: 'g:i A'
        })

        if (selectedTime.isValid()) {
          this.timepicker('setTime', selectedTime.toDate())
        }

        $clearButton.on('click', function () {
          self.timepicker('setTime', null).trigger('change')
        })
      },
      onchange: function () {
        var $valueField = this.closest('.form__field-holder').find('.datetimepicker-time-value')
        var time = moment(this.timepicker('getTime'), 'g:i A')

        if (time.isValid()) {
          $valueField.val(time.format('HH:mm:ss')).trigger('change')
        }
        else {
          $valueField.val(null).trigger('change')
        }
      },
      onunmatch: function () {
      }
    })

    $('.form-group.datetime').entwine({
      updateDisplay: function () {
        var $dateField = this.find('input[data-date]')
        var $timeField = this.find('input[data-time]')
        var $valueField = this.find('.datetimepicker-datetime-value')

        var datetime = moment($valueField.val(), moment.HTML5_FMT.DATETIME_LOCAL)

        if (datetime.isValid()) {
          $dateField.data('datepicker').setDate(datetime.toDate(), true)
          $timeField.timepicker('setTime', datetime.toDate())
        }
      },
      updateValue: function () {
        var $dateField = this.find('input[data-date]')
        var $timeField = this.find('input[data-time]')
        var $valueField = this.find('.datetimepicker-datetime-value')

        var date = moment($dateField.data('datepicker').getDate())
        var time = moment($timeField.timepicker('getTime', date))

        if (!date.isValid()) {
          $valueField.val(null).trigger('change')

          return null
        }

        if (time.isValid()) {
          $valueField.val(time.format(moment.HTML5_FMT.DATETIME_LOCAL)).trigger('change')
        }
        else {
          $valueField.val(date.format(moment.HTML5_FMT.DATETIME_LOCAL)).trigger('change')
        }
      },
      onmatch: function () {
        var $dateField = this.find('input[data-date]')
        var $timeField = this.find('input[data-time]')
        var $valueField = this.find('.datetimepicker-datetime-value')
        var $clearButton = this.find('.datetimepicker-clear')
        var datetime = moment($valueField.val(), moment.HTML5_FMT.DATETIME_LOCAL)
        var self = this

        var minDate = moment($dateField.data('min'), 'YYYY-MM-DD')
        var maxDate = moment($dateField.data('max'), 'YYYY-MM-DD')

        $timeField.timepicker({
          timeFormat: 'g:i A',
          disableTextInput: true
        })

        if (datetime.isValid()) {
          $timeField.timepicker('setTime', datetime.toDate())
        }

        $timeField.on('change', function () {
          self.updateValue()
        })

        var datepicker = new Pikaday({
          field: $dateField.get(0),
          format: 'MMM D, YYYY',
          minDate: minDate.isValid() ? minDate.toDate() : null,
          maxDate: maxDate.isValid() ? maxDate.toDate() : null
        })

        $dateField.data('datepicker', datepicker)

        $dateField.on('change', function () {
          var date = moment($dateField.val(), 'MMM D, YYYY')

          if (!date.isValid()) {
            if (datepicker.getDate() !== null) {
              datepicker.setDate(null, true)
            }
          }

          self.updateValue()
        })

        $clearButton.on('click', function () {
          if (datepicker.getDate() !== null) {
            datepicker.setDate(null, true)
            $timeField.timepicker('setTime', null).trigger('change')
            self.updateValue()
          }
        })

        this.updateDisplay()
      }
    })

    $('.form-group.datetimerangepicker').entwine({
      onmatch: function () {
        this.find('.form__fieldgroup.form__field-holder').datepair({
          anchor: null,
          defaultTimeDelta: null,
          parseDate: function (input) {
            return input.value && $(input).data('datepicker').getDate()
          },
          updateDate: function (input, dateObj) {
            $(input).data('datepicker') && $(input).data('datepicker').setDate(dateObj, true)
          },
          parseTime: function (input) {
            return input.value && $(input).timepicker('getTime')
          },
          updateTime: function (input, dateObj) {
            $(input).timepicker('setTime', dateObj).trigger('change')
          },
          setMinTime: function (input, dateObj) {
            $(input).timepicker('option', 'minTime', dateObj)
          }
        })
      }
    })

  })
})(window.jQuery)
