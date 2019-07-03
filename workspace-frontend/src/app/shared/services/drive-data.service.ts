import { Injectable } from '@angular/core';

@Injectable()
export class DriveDataService {
  constructor() { }
  /* Init Data Models for drive module[Start] */
  driveModels: any = {
    getDriveFiles: {
      page: 1,
      perPage: 10,
      usagePercentage: '',
      driveType: [],
      storage: false,
      selectedTab: '',
      driveStorage: [],
      driveFilelist: [],
      selectedDriveSlug: '',
      extensions: ['exe',
      'php',
      'pif',
      'application',
      'gadget',
      'msi',
      'msp',
      'com',
      'scr',
      'hta',
      'cpl',
      'msc',
      'jar',
      'bat',
      'cmd',
      'vb',
      'vbs',
      'vbe',
      'js',
      'jse',
      'ws',
      'wsf',
      'PS1',
      'PS1XML',
      'PS2',
      'PS2XML',
      'PSC1',
      'MSH',
      'MSH1',
      'MSH2',
      'MSHXML',
      'MSH1XML',
      'MSH2XML', 
      'PSC2',
      'scf',
      'lnk',
      'inf',
      'reg'
    ],
    },

    driveFileManagement: {
      isFolder: false,
      link: '',
      loggedUserSlug: '',
      hierarchy: [],
      showSearch: false,
      searchText: '',
      sortOption: 'name',
      sortMethod: 'asc',
      selectAllHeader: false,
      selectAllFooter: false,
      folderName: '',
      showFooter: false,
      lastFolder: '',
      fileLoading: false,
      popUpMsg: '',
      sharedUserSlug: '',
      userList: [],
      searchUser: '',
      trashAction: '',
      uploadFiles: [],
      shearedUserList: [],
      shearedDeptList: [],
      destinationSlug: '',
      destinationFolderList: [],
      sourceSlug: '',
      selectedFileSlug: [],
      popUpHierarchy: []
     },

    copyOption: {
      show: false
    },

    createFolderPopUp: {
      show: false
    },

    createDestinationFolder: {
      show: false
    },

    deletePopUp: {
      show: false
    },

    footerPopUp: {
      show: false
    },

    renameFile: {
      show: false
    },

    moveOption: {
      show: false
    },

    shareOption: {
      show: false
    },

    moreOption: {
      show: false
    },

    userCount: {
      show: false
    },
   
    uploadOptPopUp: {
      show: false
    },

    fileUpload: {
      show: false
    },

    folderUpload: {
      show: false
    },

    uploadBtn: {
      show: true
    },

    departmentList: {
      list: [],
      toDept: [],
      slug: [],
    },
  };

  getDriveFiles = { ...this.driveModels.getDriveFiles };
  driveFileManagement = JSON.parse(JSON.stringify(this.driveModels.driveFileManagement));
  copyOption = { ...this.driveModels.copyOption };
  moveOption = { ...this.driveModels.moveOption };
  shareOption = { ...this.driveModels.shareOption };
  moreOption = { ...this.driveModels.moreOption };
  userCount = { ...this.driveModels.userCount };
  uploadOptPopUp = { ...this.driveModels.uploadOptPopUp };
  fileUpload = { ...this.driveModels.fileUpload };
  folderUpload = { ...this.driveModels.folderUpload };
  createFolderPopUp = { ...this.driveModels.createFolderPopUp };
  createDestinationFolder = { ...this.driveModels.createDestinationFolder };
  renameFile = { ...this.driveModels.renameFile };
  uploadBtn = { ...this.driveModels.uploadBtn };
  deletePopUp = { ...this.driveModels.deletePopUp };
  footerPopUp = { ...this.driveModels.footerPopUp };
  departmentList = { ...this.driveModels.departmentList };
 
  /* Reset Drives */
  resetDriveFiles(): void {
    this.driveFileManagement = JSON.parse(JSON.stringify(this.driveModels.driveFileManagement));
  }
  
  /* Reset PopUps */
  resetDrivePopUps(): void {
    this.copyOption = { ...this.driveModels.copyOption };
    this.moveOption = { ...this.driveModels.moveOption };
    this.shareOption = { ...this.driveModels.shareOption };
    this.moreOption = { ...this.driveModels.moreOption };
    this.userCount = { ...this.driveModels.userCount };
    this.uploadOptPopUp = { ...this.driveModels.uploadOptPopUp };
    this.fileUpload = { ...this.driveModels.fileUpload };
    this.folderUpload = { ...this.driveModels.folderUpload };
    this.createFolderPopUp = { ...this.driveModels.createFolderPopUp };
    this.renameFile = { ...this.driveModels.renameFile };
    this.deletePopUp = { ...this.driveModels.deletePopUp };
  }
}

