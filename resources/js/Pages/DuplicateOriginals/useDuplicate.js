export default function useAlteration() {
  function getBadgeStatus(status) {
    const statusMap = {
        '100': '<span class="badge bg-warning text-default">Client Quoted</span>',
        '200': '<span class="badge bg-info text-default">Client Invoiced</span>',
        '300': '<span class="badge bg-light text-dark">Client Paid</span>',
        '400': '<span class="badge bg-warning text-default">Payment to the Liquor Board</span>',
        '500': '<span class="badge bg-secondary text-default">Duplicate Original Request Letter</span>',
        '600': '<span class="badge bg-dark text-default">Scanned Application</span>',
        '700': '<span class="badge bg-secondary text-default">Application Lodged</span>',
        '800': '<span class="badge bg-info text-default">Duplicate Original Issued</span>',
        '900': '<span class="badge bg-success text-default">Duplicate Original Delivered</span>',
    };

    const defaultStatus = '<span class="badge bg-danger text-default">Not Set</span>';

    return statusMap[status] || defaultStatus;
}

function getStatus(status) {
  const statusMap = {
    100: 'Client Quoted',
    200: 'Client Invoiced',
    300: 'Client Paid',
    400: 'Payment to the Liquor Board',
    500: 'Duplicate Original Request Letter',
    600: 'Scanned Application',
    700: 'Application Lodged',
    800: 'Duplicate Original Issued',
    900: 'Duplicate Original Delivered',
  };

  return statusMap[status] || 'Not Set';
}

    return {
        getBadgeStatus,
        getStatus
    }
    
}