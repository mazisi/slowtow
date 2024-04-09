export default function getLicenceStatus(){
    /**
 * Returns the corresponding status value based on the given status parameter.
 *
 * @param {string} status_param - The status parameter to be used for retrieving the status value.
 * @return {string} The corresponding status value based on the given status parameter, or 'Not Set' if no matching status value is found.
 */
function getPlainStatus(status_param) {
    const statusValues = {
      '100': 'Client Quoted',
      '200': 'Deposit Invoice',
      '300': 'Deposit Paid',
      '400': 'Payment to the Liquor Board',
      '500': 'Prepare New Application',
      '510': 'Premises Complete and Trading',//new status
      '600': 'Scanned Application',
      '700': 'Lodged with Municipality',
      '800': 'Municipal Comments',
      '900': 'Completed Application Scanned',
      '1000': 'Lodged with MER',
      '1100': 'Lodged with Magistrate',
      '1200': 'Lodged with DPO',
      '1300': 'Police Report',
      '1400': 'Lodged With Liquor Board',
      '1500': 'Application Lodged',
      '1600': 'Additional Documents/Information',
      '1700': 'Initial Inspection',
      '1800': 'Final Inspection',
      '1900': 'Activation Fee Requested',
      '2000': 'Client Finalisation Invoice',
      '2100': 'Finalisation Paid',
      '2200': 'Activation Fee Paid',
      '2300': 'Licence Issued',
      '2400': 'Licence Delivered',
    };
  
    return statusValues[status_param] || 'Not Set';
  }

  return{
    getPlainStatus
  }
}