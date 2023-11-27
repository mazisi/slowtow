

const PROVINCES =      ['Eastern Cape',
                        'Free State',
                        'Gauteng',
                        'KwaZulu-Natal',
                        'Limpopo',
                        'Mpumalanga',
                        'Northern Cape',
                        'North West',
                        'Western Cape'
                        ];

const BOARD_REGIONS  = ['Eastern Cape',
                        'Free State',
                        'Ekurhuleni',
                        'Johannesburg',
                        'Sedibeng',
                        'Tshwane',
                        'West Rand',
                        'KwaZulu-Natal',
                        'Limpopo',
                        'Mpumalanga',
                        'National Liquor Authority',
                        'Northern Cape',
                        'North West',
                        'Western Cape',
                      ];

    const BOARD_REGIONS_GAUTENG  = ['Eastern Cape',
                                    'Free State',
                                    'Ekurhuleni',
                                    'Johannesburg',
                                    'Sedibeng',
                                    'Tshwane',
                                    'West Rand',
                                  ];

  const COMPANY_TYPES = [
                      "Association",
                      "Close Corporation CC",
                      "Individual",
                      "Non-profit Organization (NPO)",
                      "Partnership",
                      "Private Company  (Proprietary) Limited",
                      "Public Company",
                      "Sole Proprietor",
                      "Trust"                  
                    ]

export default {
  getProvinces() {
    return PROVINCES;
  },

  getGautengProvinces() {
    return BOARD_REGIONS_GAUTENG;
  },

  getCompanyTypes() {
    return COMPANY_TYPES;
  },

  getBoardRegions() {
    return BOARD_REGIONS;
  },
  
}
