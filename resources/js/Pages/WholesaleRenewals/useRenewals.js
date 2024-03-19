export default function useAlteration() {
function getBadgeStatus(status) {
    const statusMap = {
        '100': '<span class="badge bg-dark text-default">Client Quoted</span>',
        '200': '<span class="badge bg-info text-default">Client Invoiced</span>',
        '300': '<span class="badge bg-light text-dark">Client Paid</span>',
        '400': '<span class="badge bg-warning text-default">Payment To The Liquor Board</span>',
        '500': '<span class="badge bg-secondary text-default">Renewal Issued</span>',
        '600': '<span class="badge bg-success text-default">Renewal Delivered</span>'
    };

    const defaultStatus = '<span class="badge bg-default text-default">Not Set</span>';

    return statusMap[status] || defaultStatus;
}

function getStatus(status_param){
  let status;

  switch (status_param) {
      case '100':
          status = 'Client Quoted'
          break;
      case '200':
          status = 'Client Invoiced'
          break;
      case '300':
          status = 'Client Paid'
          break;
      case '400':
          status = 'Payment To The Liquor Board'
          break;
      case '500':
          status = 'Renewal Issued'
          break;
      case '600':
          status = 'Renewal Delivered'
          break;
      default:
          status='Not Set';
          break;
  }
  return status;

  }

    return {
        getBadgeStatus,
        getStatus
    }
    
}