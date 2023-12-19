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
      '200': 'Deposit Invoiced',
      '300': 'Deposit Paid',
      '400': 'Prepare New Application',
      '500': 'Application Submitted',
      '600': 'Initial Application Fee',
      '700': 'Application Lodged',
      '800': 'Additional Documents Request',
      '900': 'NLA 6 Proposed',
      '1000': 'NLA 7 Submitted',
      '1100': 'NLA 8 Issued',
      '1200': 'Activation Fee',
      '1300': 'NLA 9 Issued',
      '1400': 'Original Licence',
      '1500': 'Original Licence Delivered',
    };
  
    return statusValues[status_param] || 'Not Set';
  }

  return{
    getPlainStatus
  }
}