import { Injectable } from '@angular/core';
import { CookieService } from 'ngx-cookie-service';


@Injectable()
export class PartnerDataService {

  orgDetails: boolean = false;

  constructor(
    private cookieService: CookieService

  ) { }

  partnerModels:any = {
    orgDetailsPop: {
      showPopup: false
    },

    changeImage: {
      file: [],
      orgSlug: '',
      resetToDefault: false,
      dashboardMsg: '',
      workReport: null,
      timezone: "Asia/Kolkatta"
    },

    changeBackgroundSettings: {
      file: [],
      partnerSlug: '',
      resetToDefault: false,
      dashboardMsg: '',
      workReport: null,
      timeZone: ''
    },

    getBackgroundSettings:{
      dashboardSettings:[]
    },
    UnlicenseDetailsPop: {
      showPopup: false
    },
    cancelPop:{
      showPopup: false
    },
    approvePop:{
      showPopup: false
    },
    cancelRequestPop:{
      showPopup: false
    },

    showDelete:{
      showPopup: true

    },
    partnerAPICall:{
      inProgress:false
    },

    getOrganisationDetails: { 
      current_page:1,
      from:1,
      last_page:1,
      per_page:10,
      to:0,
      total:0,
      organizations: []
    },

    OrganisationPageDetails:{
        page:1,
        perPage:10,
        sortOrder: 'desc',
        sortBy: 'organization',
        
    },

    licensePageDetails:{
      page:1,
      perPage:10,
      sortOrder: 'desc',
      sortBy: 'organization',
  },

    createOrganisation:{
      verticalSlug:'',
      countrySlug:'',
      partnerSlug:this.cookieService.get('partnerSlug'), 
      name:'',
      adminEmail:'',
      adminUserSlug:''
    },

    deletePopUp: {
      show: false
    },

    renewLicensePopup:{
      show:false
    },

    requestLicensePopup:{
      show:false
    },

    updateOrganisation:{
      orgSlug:'',
      verticalSlug:'',
      verticalName:'',
      countrySlug:'',
      countryName:'',
      partnerSlug:this.cookieService.get('partnerSlug'),
      name:'',
      email:'',
      adminEmails:null,
      adminUserSlug:''

    },

    updateRenewLicense:{
      name:'',
      licenseType:'',
      maxUsers:''
    },

    
    organisationDetails:{
    selectedAll:false,
    selectedOrganisationIds:[],
    showPopup: false
    },

    selectedLicenseDetails:{
      selectedAll:false,
      selectedLicenseIds:[],
      showPopup: false
      },
  

    countryTemplate:{
      countries:[]
    },

    getVerticals:{
      verticals:[]
  }, 

  createLicense:{
      maxUsers:'',
      licenseType:'',
      partnerSlug:this.cookieService.get('partnerSlug'),
      orgSlug:''
  },

  updateLicense:{
    licenseRequestSlug:'',
    maxUsers:'',
    name:'',
    licenseType:'',
    orgSlug:''
},

  getLicenseDetails:{
    current_page:1,
    from:1,
    last_page:1,
    per_page:10,
    to:0,
    total:0,
    license:[]
  },

  licenseDetails:{
    partnerSlug:this.cookieService.get('partnerSlug'),
    tab:''

  },

  OrganisationTabDetails:{
    partnerSlug:this.cookieService.get('partnerSlug'),
    tab:''

  },

  selectedRequest:{
    licenseSlug:''
  },


  renewLicense:{
    licenseKey:''
  },

  forwardLicense:{
    licenseRequestSlug:''
  },

  licenseRequestDetailsPop: {
    show : false
  },


    showCreateOrganisationpopup: {
      show: false
    },

    showEditOrganisationpopup: {
      show: false
    },
    showEditUnlicensepopup: {
      show: false
    },

    showEditOrganisation: {
      show: false
    },


    showCreateLicensepopup: {
      show: false
    },

    showEditLicensepopup: {
      show: false
    },


    getOrganisation:{
      partnerSlug:this.cookieService.get('partnerSlug'),
    },

    selectedIndex:{
      index:''
    },
    
    licenseDetailsPop:{
      showPopup: false
    },
    requestDetailsPop:{
      showPopup: false
    },
    
    selectedElement:{
      isValidated: true,

    },

    renewPopup:{
      showPopup:false
    },

    OrganisationTab:{
    show:true
    },

    LicensedOrgTab:{
    show:false
    },

    LicenseTab:{
    show:false
    },

    deleteBulkPopup: {
      show: false
  },

  applyLicensePopup:{
    show:false
  },

  renewRequestPopup:{
    show:false
  },
  
  showPartner:{
    show:false
  },
  
  getOrgLicenseDetails:{
    expiresOn:null,
    key:'',
    partner:'',
    partnerImage:'',
    requestedOn:null,
    orgName:'',
    startedOn:null,
    status:'',
    type:'',
    users:'',
    licenseButton:{
      message:'',
      status:'',

    }
  },

    selectedCurrentLicenses:{},
    selectedOrganisationDetails:{},
    selectedLicenseRequestDetails:{},

    selectedOrganisation:{},

    moreOption:{},

  
    selectedLicense:{},
    selectedRenewLicense:{},
    selectedAdminRequests:{},
    
  

  }
  updateRenewLicense = {...this.partnerModels.updateRenewLicense};
  showPartner = {...this.partnerModels.showPartner};
  getBackgroundSettings ={ ...this.partnerModels.getBackgroundSettings};
  renewRequestPopup = {...this.partnerModels.renewRequestPopup};
  showDelete = {...this.partnerModels.showDelete};
  applyLicensePopup = {...this.partnerModels.applyLicensePopup};
  getOrgLicenseDetails = { ...this.partnerModels.getOrgLicenseDetails };
  changeImage = { ...this.partnerModels.changeImage};
  changeBackgroundSettings = { ...this.partnerModels.changeBackgroundSettings};

  deleteBulkPopup = {...this.partnerModels.deleteBulkPopup};
  approvePop = {...this.partnerModels.approvePop};
  selectedIndex =  { ...this.partnerModels.selectedIndex };
  OrganisationTab=  { ...this.partnerModels.OrganisationTab };
  LicenseTab=  { ...this.partnerModels.LicenseTab };
  LicensedOrgTab=  { ...this.partnerModels.LicensedOrgTab };

  renewPopup=  { ...this.partnerModels.renewPopup };
  selectedCurrentLicenses=  { ...this.partnerModels.selectedCurrentLicenses };

  selectedOrganisationDetails=  { ...this.partnerModels.selectedOrganisationDetails };
  selectedLicenseRequestDetails=  { ...this.partnerModels.selectedLicenseRequestDetails };

  
  OrganisationPageDetails=  { ...this.partnerModels.OrganisationPageDetails };
  licensePageDetails=  { ...this.partnerModels.licensePageDetails };

  cancelPop = {...this.partnerModels.cancelPop};
  cancelRequestPop = {...this.partnerModels.cancelRequestPop};
  licenseRequestDetailsPop = { ...this.partnerModels.licenseRequestDetailsPop };  
  orgDetailsPop = { ...this.partnerModels.orgDetailsPop }
  UnlicenseDetailsPop = { ...this.partnerModels.UnlicenseDetailsPop }

  licenseDetailsPop = { ...this.partnerModels.licenseDetailsPop}

  OrganisationTabDetails=  { ...this.partnerModels.OrganisationTabDetails };

  requestDetailsPop = { ...this.partnerModels.requestDetailsPop}
  getOrganisationDetails = { ...this.partnerModels.getOrganisationDetails };
  createOrganisation = { ...this.partnerModels.createOrganisation }
  updateOrganisation = { ...this.partnerModels.updateOrganisation }
  organisationDetails = { ...this.partnerModels.organisationDetails }
  showCreateOrganisationpopup = { ...this.partnerModels.showCreateOrganisationpopup };
  showEditOrganisationpopup = { ...this.partnerModels.showEditOrganisationpopup };
  showEditUnlicensepopup = { ...this.partnerModels.showEditUnlicensepopup };

  selectedOrganisation = { ...this.partnerModels.selectedOrganisation };
  selectedLicense = { ...this.partnerModels.selectedLicense };
  selectedRenewLicense = { ...this.partnerModels.selectedRenewLicense };
  selectedAdminRequests = { ...this.partnerModels.selectedAdminRequests };
  moreOption = { ...this.partnerModels.moreOption };

  showCreateLicensepopup = { ...this.partnerModels.showCreateLicensepopup };
  showEditLicensepopup = { ...this.partnerModels.showEditLicensepopup };
  getRole=  { ...this.partnerModels.getRole };
  createLicense=  { ...this.partnerModels.createLicense };
  updateLicense=  { ...this.partnerModels.updateLicense };
  renewLicense=  { ...this.partnerModels.renewLicense };
  forwardLicense=  { ...this.partnerModels.forwardLicense };


  licenseDetails=  { ...this.partnerModels.licenseDetails };
  selectedLicenseDetails=  { ...this.partnerModels.selectedLicenseDetails };

  getLicenseDetails=  { ...this.partnerModels.getLicenseDetails };  
  selectedRequest=  { ...this.partnerModels.selectedRequest };  
  countryTemplate = { ...this.partnerModels.countryTemplate }
  getVerticals = { ...this.partnerModels.getVerticals }
  deletePopUp = { ...this.partnerModels.deletePopUp };
  partnerAPICall  = { ...this.partnerModels.partnerAPICall };
  selectedElement  = { ...this.partnerModels.selectedElement };
  renewLicensePopup  = { ...this.partnerModels.renewLicensePopup };
  requestLicensePopup  = { ...this.partnerModels.requestLicensePopup };






  resetOrganisation(): void {
    this.createOrganisation = { ...this.partnerModels.createOrganisation };
  } 

  resetLicenseSelected():void{
    this.selectedLicenseDetails = {...this.partnerModels.selectedLicenseDetails}
  }

  
  resetOrganisationDetails():void{
    this.partnerModels.organisationDetails.selectedOrganisationIds = [];
    this.organisationDetails = {...this.partnerModels.organisationDetails}
  }

  resetSettings():void{
    this.partnerModels.changeBackgroundSettings.file = [];
    this.changeBackgroundSettings = {...this.partnerModels.changeBackgroundSettings};
  }
  resetLicenseDetails():void{
    this.partnerModels.selectedLicenseDetails.selectedLicenseIds = [];
    this.selectedLicenseDetails = {...this.partnerModels.selectedLicenseDetails}
  }


  resetLicenses(): void {
    this.createLicense = { ...this.partnerModels.createLicense };
  } 

  resetLicenseRequest():void {
    this.selectedRequest ={ ...this.partnerModels.selectedRequest};
  }

}