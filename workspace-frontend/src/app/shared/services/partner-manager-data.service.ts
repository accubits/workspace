import { Injectable } from '@angular/core';

@Injectable()
export class PartnerManagerDataService {

  constructor() { }

  partnerManagerModels:any = {
    
        getPartnersDetails: { 
          current_page:1,
          from:1,
          last_page:1,
          per_page:10,
          to:0,
          total:0,
          partners: []
        },

        getPartner:{
          name:''
        },
  }

  getPartnersDetails = { ...this.partnerManagerModels.getPartnersDetails };
  getPartner = { ...this.partnerManagerModels.getPartner };


}
