export default function useAlteration() {
function getBadgeStatus(status) {
    const statusMap = {
        '100': '<span class="badge bg-dark text-default">Client Quoted</span>',
        '200': '<span class="badge bg-info text-default">Client Invoiced</span>',
        '300': '<span class="badge bg-light text-dark">Client Paid</span>',
        '400': '<span class="badge bg-warning text-default">Payment to the Liquor Board</span>',
        '500': '<span class="badge bg-secondary text-default">Select Nominees</span>',
        '600': '<span class="badge bg-secondary text-default">Prepare Nomination Application</span>',
        '700': '<span class="badge bg-secondary text-default">Scanned Application</span>',
        '800': '<span class="badge bg-success text-default">Nomination Lodged</span>',
        '900': '<span class="badge bg-success text-default">Nomination Issued</span>',
        '1000': '<span class="badge bg-success text-default">Nomination Delivered</span>',
    };

    const defaultStatus = '<span class="badge bg-default text-default">Not Set</span>';

    return statusMap[status] || defaultStatus;
}

function getPlainStatus(status){
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
      return 'Select Nominees'
    }
    if(status == 600){
      return 'Prepare Nomination Application'
    }
    if(status == 700){
      return 'Scanned Application'
    }
    if(status == 800){
      return 'Nomination Lodged'
    }
    if(status == 900){
      return 'Nomination Issued'
    }
    if(status == 1000){
      return 'Nomination Delivered'
    }else{
      return 'Not Set'
    }

  }

    return {
        getBadgeStatus,
        getPlainStatus
    }
    
}