export default function useAlteration() {
function getBadgeStatus(status) {
    const statusMap = {
        '100': '<span class="badge bg-dark text-default">Client Quoted</span>',
        '200': '<span class="badge bg-info text-default">Client Invoiced</span>',
        '300': '<span class="badge bg-danger text-dark">Client Paid</span>',
        '400': '<span class="badge bg-warning text-default">Payment To The Liquor Board</span>',
        '500': '<span class="badge bg-secondary text-default">Renewal Issued</span>',
        '600': '<span class="badge bg-success text-default">Renewal Delivered</span>'
    };

    const defaultStatus = '<span class="badge bg-default text-default">Not Set</span>';

    return statusMap[status] || defaultStatus;
}

function getWholesaleBadgeStatus(status) {
    const statusMap = {
        '100': '<span class="badge bg-dark text-default">Renewal Notice Received</span>',
        '200': '<span class="badge bg-info text-default">Turnover Information Requested</span>',
        '300': '<span class="badge bg-danger text-dark">Turnover Information Received</span>',
        '400': '<span class="badge bg-warning text-default">Annual Return Submited</span>',
        '500': '<span class="badge bg-secondary text-default">Client Invoiced</span>',
        '600': '<span class="badge bg-primary text-default">Client Paid</span>',
        '700': '<span class="badge bg-info text-default">Payment to the National Liquor Authority</span>',
        '800': '<span class="badge bg-info text-default">Renewal Forms Sent</span>',
        '900': '<span class="badge bg-success text-default">Renewal Forms Received</span>',
        '1000': '<span class="badge bg-secondary text-default">Renewal Forms Preparation</span>',
        '1100': '<span class="badge bg-warning text-default">Renewal Submitted</span>',
        '1200': '<span class="badge bg-success text-default">Additional Documents/Information Requested</span>',
        '1300': '<span class="badge bg-info text-default">Renewal Pending QA</span>',
        '1400': '<span class="badge bg-primary text-default">Renewal Awaiting Sign Off</span>',
        '1500': '<span class="badge bg-dark text-default">Renewal Approved</span>'
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
        getStatus,
        getWholesaleBadgeStatus
    }
    
}