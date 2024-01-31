export default function useAlteration() {
  function getBadgeStatus(status) {
    const statusMap = {
        '100': '<span class="badge bg-default text-default">Client Quoted</span>',
        '200': '<span class="badge bg-info text-default">Client Invoiced</span>',
        '300': '<span class="badge bg-light text-dark">Client Paid</span>',
        '400': '<span class="badge bg-warning text-default">Payment to the Liquor Board</span>',
        '500': '<span class="badge bg-secondary text-default">Duplicate Original Request Letter</span>',
        '600': '<span class="badge bg-dark text-default">Scanned Application</span>',
        '700': '<span class="badge bg-secondary text-default">Application Lodged</span>',
        '800': '<span class="badge bg-default text-default">Duplicate Original Issued</span>',
        '900': '<span class="badge bg-success text-default">Duplicate Original Delivered</span>',
    };

    const defaultStatus = '<span class="badge bg-danger text-default">Not Set</span>';

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
      return 'Payment to the Liquor Board'
    }

    if(status == 500){
      return 'Duplicate Original Request Letter'
    }

    if(status == 600){
      return 'Scanned Application'
    }
    if(status == 700){
      return 'Application Lodged'
    }
    if(status == 800){
      return 'Duplicate Original Issued'
    }
    if(status == 900){
      return 'Duplicate Original Delivered'
    }else{
      return 'Not Set'
    }

  }

    return {
        getBadgeStatus,
        getStatus
    }
    
}