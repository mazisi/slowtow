export default function useAlteration() {
function getBadgeStatus(status) {
    const statusMap = {
        '100': '<span class="badge bg-dark text-default">Client Quoted</span>',
        '200': '<span class="badge bg-info text-default">Client Invoiced</span>',
        '300': '<span class="badge bg-light text-dark">Client Paid</span>',
        '400': '<span class="badge bg-warning text-default">Prepare Alterations Application</span>',
        '500': '<span class="badge bg-secondary text-default">Payment to the Liquor Board</span>',
        '600': '<span class="badge bg-secondary text-default">Alterations Lodged</span>',
        '700': '<span class="badge bg-secondary text-default">Alterations Certificate Issued</span>',
        '800': '<span class="badge bg-success text-default">Alterations Delivered</span>',
    };

    const defaultStatus = '<span class="badge bg-default text-default">Not Set</span>';

    return statusMap[status] || defaultStatus;
}

function getStatus(status){
    if(status == 100){
      return 'Client Quoted'
    }
    if(status == 200){
      return 'Client Invoiced'
    }
    if(status == 300){
      return 'Client Paid'
    }
    if(status == 400){
      return 'Prepare Alterations Application'
    }
    if(status == 500){
      return 'Payment to the Liquor Board'
    }
    if(status == 600){
      return 'Alterations Lodged'
    }
    if(status == 700){
      return 'Alterations Certificate Issued'
    }
    if(status == 800){
      return 'Alterations Delivered'
    }else{
      return 'Not Set'
    }

  }

    return {
        getBadgeStatus,
        getStatus
    }
    
}