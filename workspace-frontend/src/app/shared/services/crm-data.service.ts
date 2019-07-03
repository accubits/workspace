import { Injectable } from '@angular/core';

@Injectable()
export class CrmDataService {

  constructor() { }
  crmModels: any = {
    moreOption: {
      show: false
    },
    leadsDetail: {
      show: false
    },
    customerDetail: {
      show: false
    },
    editProfile: {
      show: false
    }
  };
  moreOption = { ...this.crmModels.moreOption};
  leadsDetail = { ...this.crmModels.leadsDetail};
  customerDetail = { ...this.crmModels.customerDetail};
  editProfile = { ...this.crmModels.editProfile};
}
