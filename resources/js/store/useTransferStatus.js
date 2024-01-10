export default function getTransferStatus(){
    /**
 * Returns the corresponding status value based on the given status parameter.
 *
 * @param {string} status_param - The status parameter to be used for retrieving the status value.
 * @return {string} The corresponding status value based on the given status parameter, or 'Not Set' if no matching status value is found.
 */


  function getPlainStatus(status) {
    switch (status) {
      case '1':
        return 'Client Quoted';
      case '2':
        return 'Client Invoiced';
      case '3':
        return 'Client Paid';
      case '4':
        return 'Prepare Transfer Application';
      case '5':
        return 'Payment To The Liquor Board';
      case '6':
        return 'Scanned Application';
      case '7':
        return 'Application Lodged';
      case '8':
        return 'Activation Fee Paid';
      case '9':
        return 'Transfer Issued';
      case '10':
        return 'Transfer Delivered';
      default:
        return 'Not Set';
    }
  }
  
  

  return{
    getPlainStatus
  }
}