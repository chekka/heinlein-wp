// Code from: https://github.com/orlyyani/read-more
jQuery(document).ready(() => {
  Vue.directive('readmore', {
    twoWay: true,
    bind: function (el, bind, vn) {
      let val_container = bind.value

      if (bind.value.length > bind.arg) {
        vn.elm.textContent = bind.value.substring(0, bind.arg)
        let read_more = document.createElement('a')
        read_more.href = '#'
        read_more.text = '... [read more]'

        let read_less = document.createElement('a')
        read_less.href = '#'
        read_less.text = '[read less]'

        vn.elm.append(' ', read_more)

        read_more.addEventListener('click', function () {
          vn.elm.textContent = val_container
          vn.elm.append(' ', read_less)
        })

        read_less.addEventListener('click', function () {
          vn.elm.textContent = bind.value.substring(0, bind.arg)
          vn.elm.append(' ', read_more)
        })
      } else {
        vn.elm.textContent = bind.value
      }
    },
  })

  Vue.use('readmore')
})
