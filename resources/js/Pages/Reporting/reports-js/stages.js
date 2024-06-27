const RENEWAL_STAGES = {
  "100": "Client Quoted",
  "200" : "Client Invoiced",
  "300" : "Client Paid",
  "400":  "Payment To Liquor Board",
  "500": "Renewal Issued",
  "600": "Renewal Delivered"
  }

  const TRANSFER_STAGES = {
    "100" : "Client Quoted",
    "200" : "Client Invoiced",
    "300" : "Client Paid",
    "400" : "Prepare Transfer Application",
    "500" : "Payment To The Liquor Board",
    "600" : "Scanned Application",
    "700" : "Application Logded",
    "800" : "Activation Fee Paid",
    "900" : "Transfer Issued",
    "1000" : "Transfer Delivered"
  }

  const NOMINATION_STAGES = {
    "100" : "Client Quoted",
    "200" : "Client Invoiced",
    "300" : "Client Paid",
    "400" : "Payment to the Liquor Board",
    "500" : "Select nominees",
    "600" : "Prepare Nomination Application", 
    "700" : "Scanned Application",
    "800" : "Nomination Lodged", 
    "900" : "Nomination Issued", 
    "1000" : "Nomination Delivered" 
  }

  const ALTERATION_STAGES = {
      "100" : "Client Quoted",
      "200" : "Client Invoiced",
      "300" : "Client Paid", 
      "400" : "Prepare Alterations Application",
      "500" : "Payment to the Liquor Board",
      "600" : "Alterations Lodged",
      "700" : "Alterations Certificate Issued",
      "800" : "Alterations Delivered",

  }

  const TEMP_LICENCE_STAGES = {
   "100" : "Client Quoted",
   "200" : "Client Invoiced",
   "300" : "Client Paid",
   "400" : "Collate Temporary Licence Documents ",
   "500" : "Payment To The Liquor Board ",
   "600" : "Scanned Application",
   "700" : "Temporary Licence Lodged",  
   "800" : "Temporary Licence Issued", 
   "900" : "Temporary Licence Delivered", 
  }

  const NEW_APP_STAGES = {

    "100" : "Client Quoted",
    "200" : "Deposit Invoice",
    "300": "Deposit Paid",
    "400": "Payment to the Liquor Board",
    "500": "Prepare New Application",
    "600": "Scanned Application",
    "700": "Application Lodged",
    "800": "Initial Inspection",
    "900": "Liquor Board Requests",
    "1000": "Final Inspection",
    "1100": "Activation Fee Requested",
    "1200": "Client Finalisation Invoice",
    "1300": "Client Paid",
    "1400": "Activation Fee Paid",
    "1500": "Licence Issued",
    "1600": "Licence Delivered",

}

const MONTHS = {
  "100": "January",
  "200" : "February",
  "300" : "March",
  "400":   "April",
  "500": "May",
  "600": "June",
  "700": "July",
  "800": "August",
  "900": "September",
  "1000": "October",
  "1100": "November",
  "1200": "December",
}
export default{
  getRenewalStages() {
    return RENEWAL_STAGES;
  },

  getNewAppStages() {
    return NEW_APP_STAGES;
  },

  getTransferStages() {
    return TRANSFER_STAGES;
  },

  getNominationStages(){
    return NOMINATION_STAGES;
  },

  getAlterationStages(){
    return ALTERATION_STAGES;
  },

  getTempLicenceStages(){
    return TEMP_LICENCE_STAGES;
  },

  getMonths(){
    return MONTHS;
  },

}
 