( function( $ ) {
  const publicData = {
    sandBox: true,
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
      if ( publicData.sandBox ) {
        publicData.apiUrl = `https://f69d-2405-8d40-cf0-94c1-a156-7cb4-f13c-380d.ngrok.io/${tail}`
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
        config.validateId( idValue )
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
        console.log( res )
        config.closeLoading()
        const status = res.data.api_status
        const message = res.data.api_message

        Swal.fire({
          icon: 'info',
          title: 'Sandbox: API Request',
          text: `Status: ${status} -- Message: ${message}`
        })
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