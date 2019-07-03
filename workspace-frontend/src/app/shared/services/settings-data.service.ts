import { LicenseHistoryComponent } from './../../settings/license-history/license-history.component';
import { Injectable } from '@angular/core';
import { CookieService } from 'ngx-cookie-service';


@Injectable()
export class SettingsDataService {

  constructor(
    private cookieService: CookieService

  ) { }

  /* Init Data Models for settings module[Start] */
  settingsModal = {

    editProfile: {
      file: [],
      first_name: '',
      last_name: '',
      birth_date: '',
      interests: [],
      show: false
    },
    changePassword: {
      oldPassword: '',
      newPassword: '',
      confirmPwd: '',
      show: false
    },
    enable2FA: {
      show: false
    },
    renewLicence:
    {
show: false
    },
    editSettingsTemplate: {
      name: '',
      email: '',
      firstName: '',
      lastName: "",
      birthDate: null,
      userImage: "",
      id: '',
      imageUrl: "",
      interest: [],
    },

    getLicenseDetails:{
      expiresOn:null,
      key:'',
      partner:'',
      partnerImage:'',
      requestedOn:null,
      orgName:'',
      startedOn:null,
      status:'',
      type:'',
      users:{
        totalUsers:'',
        licensedUsers:''
      },
      licenseButton:{
        message:'',
        status:'',

      }
    },
    renewLicense:{
      licenseKey:''
    },

    createLicense:{
      maxUsers:'',
      licenseType:'',
      orgSlug:this.cookieService.get(' orgSlug'),
    },

    updateRenewLicense:{
      name:'',
      licenseType:'',
      maxUsers:''
    },
    selectedElement:{
      isValidated: true,

    },

    LicenseHistory: {
      show: false
    },

    renewRequestPopup:{
      show:false
    },


    deletePopUp:{
      show:false
    },


    getBackgroundSettings:{
      dashboardSettings:{
        'imageUrl': '',
        'dashboardMsg': '',
        'timezone': '',
        'frequencyName': ''
      }
    },

    changeBackgroundSettings: {
      file: [],
      orgSlug: '',
      resetToDefault: false,
      dashboardMsg: '',
      workReport: null,
      timezone: ''
    },
  };

  /* Init Data Models for settings module[End] */

  getBackgroundSettings = { ...this.settingsModal.getBackgroundSettings};
  selectedElement  = { ...this.settingsModal.selectedElement };
  renewLicense=  { ...this.settingsModal.renewLicense };
  deletePopUp = {...this.settingsModal.deletePopUp};
  changeBackgroundSettings = { ...this.settingsModal.changeBackgroundSettings};
createLicense = {...this.settingsModal.createLicense};
updateRenewLicense = {...this.settingsModal.updateRenewLicense};
renewRequestPopup  = { ...this.settingsModal.renewRequestPopup };

  getLicenseDetails = { ...this.settingsModal.getLicenseDetails };
  editProfile = { ...this.settingsModal.editProfile };


  changePassword = { ...this.settingsModal.changePassword };
  enable2FA = { ...this.settingsModal.enable2FA };
  editSettingsTemplate = { ...this.settingsModal.editSettingsTemplate };
  renewLicence = {...this.settingsModal.renewLicence};
  licenseHistory = {...this.settingsModal.LicenseHistory};
  resetPassword(): void {
    this.changePassword = { ...this.settingsModal.changePassword };
  }

  resetLicenses(): void {
    this.createLicense = { ...this.settingsModal.createLicense };
  }
  resetEditSettings(): void {
    this.editProfile = { ...this.settingsModal.editProfile };
  }
}
