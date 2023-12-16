export default function useAlteration() {
function getBadgeStatus(status_param) {
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

    return statusMap[status_param] || defaultStatus;
}

    return {
        getBadgeStatus
    }
    
}