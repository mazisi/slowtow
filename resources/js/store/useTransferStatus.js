export default function getTransferStatus(){
    /**
 * Returns the corresponding status value based on the given status parameter.
 *
 * @param {string} status_param - The status parameter to be used for retrieving the status value.
 * @return {string} The corresponding status value based on the given status parameter, or 'Not Set' if no matching status value is found.
 */
    function getBadgeStatus(status) {
      const statusMap = {
          '100': '<span class="badge bg-danger text-default">Client Quoted</span>',
          '200': '<span class="badge bg-info text-default">Client Invoiced</span>',
          '300': '<span class="badge bg-light text-dark">Client Paid</span>',
          '400': '<span class="badge bg-warning text-default">Prepare Alterations Application</span>',
          '500': '<span class="badge bg-secondary text-default">Payment to the Liquor Board</span>',
          '600': '<span class="badge bg-dark text-default">Alterations Lodged</span>',
          '700': '<span class="badge bg-info text-default">Alterations Certificate Issued</span>',
          '800': '<span class="badge bg-success text-default">Alterations Delivered</span>',
          '900': '<span class="badge bg-dark text-default">Alterations Delivered</span>',
          '1000': '<span class="badge bg-success text-default">Alterations Delivered</span>',
      };
  
      const defaultStatus = '<span class="badge bg-default text-default">Not Set</span>';
  
      return statusMap[status] || defaultStatus;
  }

  function getPlainStatus(status) {
    switch (status) {
      case '100':
        return 'Client Quoted';
      case '200':
        return 'Client Invoiced';
      case '300':
        return 'Client Paid';
      case '400':
        return 'Prepare Transfer Application';
      case '500':
        return 'Payment To The Liquor Board';
      case '600':
        return 'Scanned Application';
      case '700':
        return 'Application Lodged';
      case '800':
        return 'Activation Fee Paid';
      case '900':
        return 'Transfer Issued';
      case '1000':
        return 'Transfer Delivered';
      default:
        return 'Not Set';
    }
  }
  
  

  return{
    getBadgeStatus,
    getPlainStatus
  }
}