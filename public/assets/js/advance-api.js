( function( $ ) {
  const publicData = {
    devMode: false,
    apiUrl: "",
    fileName: "advance-api.js",
    businessVerified: false,
    loading: null,
  }

  const config = {
    /**
     * @description Initialize functions
     */
    init: function() {
      if ( this.isCartPage() ) {
        console.log( `File Name: ${publicData.fileName}` )

        this.setUrl()
        this.hideCheckout()
        this.events()
      }
    },

    /**
     * @description Set the API URL if dev mod
     */
    setUrl: function() {
      const tail = "api/v1/validate-id"
      if ( publicData.devMode ) {
        publicData.apiUrl = `https://2088-2405-8d40-cf0-94c1-d09e-f734-1088-dfc6.ngrok.io/${tail}`
      } else {
        publicData.apiUrl = `https://advancediagnostics.herokuapp.com/${tail}`
      }
    },

    /**
     * @description Hide checkout buttons
     */
    hideCheckout: function() {
      const hideCss = {
        "opacity": "0",
        "position": "absolute",
        "left": "-999999px",
        "top": "-999999px",
        "z-index": "-999999"
      }
      $( '.checkout.btn-actions' ).css( hideCss )
    },

    /**
     * @description Initialize DOM events
     */
    events: function() {
      $( document ).on( 'submit', 'form.cart-form', function( e ) {
        if ( ! publicData.businessVerified ) {
          e.preventDefault()
          config.checkId()
        }
      } )
    },

    /**
     * @description Check the Business id
     */
    checkId: function() {
      const idInput = '#id--checker-input'
      const idValue = $( idInput ).val()

      if ( ! idValue || idValue == '' ) {
        Swal.fire({
          icon: 'info',
          title: 'Please enter your Business ID'
        })
        return false

      } else {
        const isValid = config.isValid( idValue )
        if ( isValid ) config.validateId( idValue )
      }
    },

    /**
     * @description Validation Bussiness ID via API
     * @param {*} id Business ID from the form input
     */
    validateId: function( id ) {
      config.showLoading()

      fetch( publicData.apiUrl, {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        body: JSON.stringify( { id:id  } )
      } ).then( r => r.json() ).then( res => {
        config.closeLoading()
        const title = 'Business ID Verification'

        if ( res.success ) {
          const status = res.data.api_status
          const message = res.data.api_message
          const fire = {
            icon: status == 200 ? 'success' : 'info',
            title: title,
            text: message
          }

          if ( status == 200 ) window.location.href = '/checkout'

          Swal.fire( fire )

        } else {
          Swal.fire({
            icon: 'warning',
            title: title,
            text: res.message
          })
        }
      } )
    },

    /**
     * @description Check if page is Cart page then initialize events/triggers
     */
    isCartPage: function() {
      const pathname = window.location.pathname

      if ( pathname == '/cart' ) return true
      return false
    },

    /**
     * @description Check if ID is valid
     * @param {@d} value Value to validate
     */
    isValid: function( value ) {
      const regex = /^[0-9]+$/
      const numbersOnly = value.match( regex )

      if ( value.length < 12 ) {
        Swal.fire({
          icon: 'info',
          title: 'Business ID must be exactly 12 numbers'
        })
        return false
      }
      
      if ( ! numbersOnly ) {
        Swal.fire({
          icon: 'info',
          title: 'Business ID must be numbers only'
        })
        return false
      }

      return numbersOnly
    },

    /**
     * @description Show a loading feature using Sweetalert2
     * @param {*} title Title of the Swal loading
     */
    showLoading: function( title = "Processing request" ) {
      publicData.loading = Swal.fire({
        title: title,
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading()
        }
      })
    },

    /**
     * @description Close the loading feature using Sweetalert2
     */
    closeLoading: function() {
      publicData.loading.close()
    }
  }

  $( document ).ready( function() {
    config.init()
  } )

} )( jQuery )