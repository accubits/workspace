import { Injectable } from '@angular/core';

@Injectable()
export class SuperAdminDataService {

  constructor() { }

  superAdmin:any = {
    getCountryList: {
      countries : []
    },

    createForm: {
      show : false
    },

    countryDetails: {
      action: 'create',
      slug: null,
      name: '',
      isActive: true,
    },

    verticalDetails: {
      action: 'create',
      slug: null,
      name: '',
      description: '',
      isActive: true,
    },

    getVerticalList: {
      verticals : []
    },

    licenseDetailsPop:{
      showPopup: false
    },

    sharePop:{
      showPopup: false
    },

    orgDetailPop:{
      show : false
    },

    createLicense:{
      show: false
    },

    deleteMessage: {
      msg: ''
    },

    deletePopUp: {
      show: false
    },

    more:{
      show: false
    },

    createCountry:{
      show: false
    },

    createVertical:{
      show: false
    },
    fiterPopup: {
      show: false
    },
    optionBtn: {
      show: false
    },

    selectedElement:{
      isValidated: true,

    },

    expiredDetail: {
      show: false
    },

    awaitingFilter: {
      show: false
    },

    awaitingDetail: {
      show: false
    },

    licenseFilter:{
      show: false
    },

    publishPop:{
      show: false
    },

    requestDetail:{
      show: false
    },

    requestFilter:{
      show: false
    },

    createNewLicense:{
      name: '',
      maxUsers: '',
      licenseType: 'Annual',
      partnerSlug: '',
      orgSlug: ''
    },
    createSubAdmin: {
      show: false
    },
    permissionPop: {
      show: false
    },
    subAdminDetailPop: {
      show: false
    },
    changePassword: {
      show: false
    },
    editProfile: {
      show: false
    },
    enable2FA: {
      show: false
    },
    addPartner: {
      show: false
    },
  };


  publishPop = {...this.superAdmin.publishPop};
  createForm = {...this.superAdmin.createForm};
  sharePop = {...this.superAdmin.sharePop};
  orgDetailPop = { ...this.superAdmin.orgDetailPop};
  createNewLicense = { ...this.superAdmin.createNewLicense};
  requestFilter = {...this.superAdmin.requestFilter};
  requestDetail = {...this.superAdmin.requestDetail};
  licenseFilter = {...this.superAdmin.licenseFilter};
  optionBtn = { ...this.superAdmin.optionBtn };
  selectedElement = {...this.superAdmin.selectedElement};
  getCountryList = {...this.superAdmin.getCountryList};
  getVerticalList = {...this.superAdmin.getVerticalList};
  licenseDetailsPop = {...this.superAdmin.licenseDetailsPop};
  createLicense = {...this.superAdmin.createLicense};
  more = {...this.superAdmin.more};
  deleteMessage = { ...this.superAdmin.deleteMessage };
  deletePopUp = { ...this.superAdmin.deletePopUp };
  countryDetails = { ...this.superAdmin.countryDetails };
  verticalDetails = { ...this.superAdmin.verticalDetails };
  createCountry = {...this.superAdmin.createCountry};
  createVertical = {...this.superAdmin.createVertical};
  filterPopup = { ...this.superAdmin.filterPopup};
  expiredDetail = {...this.superAdmin.expiredDetail };
  awaitingFilter = {...this.superAdmin.awaitingFilter};
  awaitingDetail = {...this.superAdmin.awaitingDetail};
  createSubAdmin = {...this.superAdmin.createSubAdmin};
  permissionPop = {...this.superAdmin.permissionPop};
  subAdminDetailPop = {...this.superAdmin.subAdminDetailPop};
  changePassword = {...this.superAdmin.changePassword};
  editProfile = {...this.superAdmin.editProfile};
  enable2FA = { ...this.superAdmin.enable2FA};
  addPartner = {...this.superAdmin.addPartner};
}
