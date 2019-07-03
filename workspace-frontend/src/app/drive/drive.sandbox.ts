import { Injectable } from '@angular/core';
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
import { DriveDataService } from '../shared/services/drive-data.service';
import { DriveApiService } from '../shared/services/drive-api.service';
import { Router } from '@angular/router';
import { ToastService } from '../shared/services/toast.service';
import { CookieService } from 'ngx-cookie-service';

@Injectable()
export class DriveSandbox {
  constructor(
    private cookieService: CookieService,
    public driveDataService: DriveDataService,
    private driveApiService: DriveApiService,
    private toastService: ToastService,
    private spinner: Ng4LoadingSpinnerService,
    private router: Router
  ) { }

  /* Sandbox to handle API call for getting drive Type List[Start] */
  getDriveTypeList() {
    this.spinner.show();
    // Accessing drive API service
    return this.driveApiService.getDriveTypeList().subscribe((result: any) => {
      for (var i = 0; i < result.data.length; i++) {
        switch (result.data[i].displayName) {
          case "My Drive":
            this.driveDataService.getDriveFiles.driveType.push({ displayName: result.data[i].displayName, slug: result.data[i].slug, routerLink: 'my-drive' });
            if (this.router.url.indexOf("my-drive") !== -1) {
              this.driveDataService.getDriveFiles.selectedDriveSlug = result.data[i].slug
              this.router.navigate([], { queryParams: { slug: this.driveDataService.getDriveFiles.selectedDriveSlug } });
            }
            break;
          case "Company Drive":
            this.driveDataService.getDriveFiles.driveType.push({ displayName: result.data[i].displayName, slug: result.data[i].slug, routerLink: 'company-drive' });
            if (this.router.url.indexOf("company-drive") !== -1) {
              this.driveDataService.getDriveFiles.selectedDriveSlug = result.data[i].slug
              this.router.navigate([], { queryParams: { slug: this.driveDataService.getDriveFiles.selectedDriveSlug } });
            }
            break;
          case "Shared With Me":
            this.driveDataService.getDriveFiles.driveType.push({ displayName: 'Shared with me', slug: result.data[i].slug, routerLink: 'share-with-me' });
            if (this.router.url.indexOf("share-with-me") !== -1) {
              this.driveDataService.getDriveFiles.selectedDriveSlug = result.data[i].slug
              this.router.navigate([], { queryParams: { slug: this.driveDataService.getDriveFiles.selectedDriveSlug } });
            }
            break;
          case "Trash":
            this.driveDataService.getDriveFiles.driveType.push({ displayName: result.data[i].displayName, slug: result.data[i].slug, routerLink: 'trash' });
            if (this.router.url.indexOf("trash") !== -1) {
              this.driveDataService.getDriveFiles.selectedDriveSlug = result.data[i].slug
              this.router.navigate([], { queryParams: { slug: this.driveDataService.getDriveFiles.selectedDriveSlug } });
            }
            break;
        }
      }
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /* Sandbox to handle API call for getting drive Type List[end] */

  /* Sandbox to handle API call for getting drive file list[Start] */
  getFileList() {
    this.spinner.show();
    // Accessing drive API service
    return this.driveApiService.getFileList().subscribe((result: any) => {
      this.driveDataService.getDriveFiles.driveStorage = result.data.storage;
      this.driveDataService.getDriveFiles.driveFilelist = result.data.drive;
      this.driveDataService.getDriveFiles.storage = true;
      this.driveDataService.driveFileManagement.fileLoading = false
      this.driveDataService.driveFileManagement.selectAllHeader = false;
      this.driveDataService.driveFileManagement.selectAllFooter = false;
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /* Sandbox to handle API call for getting drive file list[end] */


  /* Sandbox to handle API call for open folder[Start] */
  destinationFolderList() {
    this.spinner.show();
    // Accessing drive API service
    return this.driveApiService.destinationFolderList().subscribe((result: any) => {
      this.driveDataService.driveFileManagement.destinationFolderList = result.data.drive;
      this.driveDataService.driveFileManagement.fileLoading = false;
      this.driveDataService.driveFileManagement.selectAllHeader = false;
      this.driveDataService.driveFileManagement.selectAllFooter = false;
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /* Sandbox to handle API call for open folder[end] */

  /* Sandbox to handle API call for search file[start] */
  public searchFile() {
    this.spinner.show();
    // Accessing drive API service
    return this.driveApiService.getSearchFile().subscribe((result: any) => {
      this.driveDataService.getDriveFiles.driveFilelist = result.data.drive;
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /* Sandbox to handle API call for search file[End] */

  /* Sandbox to handle API call for search User[start] */
  public searchUser() {
    this.spinner.show();
    // Accessing drive API service
    return this.driveApiService.getUserList().subscribe((result: any) => {
      this.driveDataService.driveFileManagement.userList = result.data;
      let userExist = this.driveDataService.driveFileManagement.userList.filter(
        user => user.slug === this.driveDataService.driveFileManagement.loggedUserSlug)[0];
        if (userExist) {
          let idx = this.driveDataService.driveFileManagement.userList.indexOf(userExist)
          this.driveDataService.driveFileManagement.userList[idx]['existing'] = true;
        }
        if (this.driveDataService.driveFileManagement.shearedUserList.length > 0) {
        for (let i = 0; i < this.driveDataService.driveFileManagement.shearedUserList.length; i++) {
          let selUserinList = this.driveDataService.driveFileManagement.userList.filter(
            user => user.slug === this.driveDataService.driveFileManagement.shearedUserList[i].userSlug)[0];
            if (selUserinList) {
              let idx = this.driveDataService.driveFileManagement.userList.indexOf(selUserinList)
              this.driveDataService.driveFileManagement.userList[idx]['existing'] = true;
            }
          }
      }
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /* Sandbox to handle API call for search User[End] */

  /* Sandbox to handle API call for upload file[start] */
  public uploadFile() {
    this.spinner.show();
    // Accessing drive API service
    return this.driveApiService.uploadFile().subscribe((result: any) => {
      this.getFileList();
      this.driveDataService.fileUpload.show = false;
      this.driveDataService.uploadOptPopUp.show = false;
      this.driveDataService.driveFileManagement.uploadFiles = [];
      this.spinner.hide();
    },
      error => {
        this.spinner.hide();
        this.toastService.Error('File cant be greater than 5 MB');
      })
  }
  /* Sandbox to handle API call for upload file[End] */

  /* Sandbox to handle API call for user List[start] */
  public userList() {
    this.spinner.show();
    // Accessing drive API service
    return this.driveApiService.getUserList().subscribe((result: any) => {
      this.driveDataService.driveFileManagement.userList = result.data;
     
       this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /* Sandbox to handle API call for user List[End] */

   /* Sandbox to handle API call for getting  department list[Start] */
   getAllDepartment() {
    this.spinner.show();
    // Accessing task API service
    return this.driveApiService.getAllDepartment().subscribe((result: any) => {
      this.driveDataService.departmentList.list = result.data.departments;
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /* Sandbox to handle API call for getting  department list[End] */

  /* Sandbox to handle API call for create folder[start] */
  createFolder() {
    this.spinner.show();
    // Accessing drive API service
    return this.driveApiService.createFolder().subscribe((result: any) => {
      this.driveDataService.driveFileManagement.folderName = '';
      this.getFileList();
      this.spinner.hide();
      this.toastService.Success(result.data.message);
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /* Sandbox to handle API call for create folder[End] */

  /* Sandbox to handle API call for create copy folder[start] */
  createCopyFolder() {
    this.spinner.show();
    // Accessing drive API service
    return this.driveApiService.createFolder().subscribe((result: any) => {
      this.driveDataService.driveFileManagement.folderName = '';
      this.destinationFolderList();
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /* Sandbox to handle API call for create copy folder[End] */

  /* Sandbox to handle API call for delete file[Start] */
  deleteFile() {
    this.spinner.show();
    // Accessing drive API service
    return this.driveApiService.deleteFiles().subscribe((result: any) => {
      this.getFileList();
      this.spinner.hide();
      this.toastService.Success(result.data.message);
    },
      err => {
        console.log(err);
        this.toastService.Error('User has no permission to delete Data');
        this.spinner.hide();
      })
  }
  /*  Sandbox to handle API call for delete file[end] */

  /* Sandbox to handle API call for delete file from Trash[Start] */
  deleteTrashFile() {
    this.spinner.show();
    // Accessing drive API service
    return this.driveApiService.deleteTrashFile().subscribe((result: any) => {
      this.getFileList();
      this.spinner.hide();
      this.toastService.Success(result.data.message);
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /*  Sandbox to handle API call for delete file from Trash[end] */

  /*  Sandbox to handle API call for copy file[Start] */
  copyFile() {
    this.spinner.show();
    this.driveDataService.copyOption.show = false;
    // Accessing drive API service
    return this.driveApiService.copyFile().subscribe((result: any) => {
      this.driveDataService.driveFileManagement.selectedFileSlug = [];
      this.getFileList();
      this.spinner.hide();
      this.toastService.Success(result.data.message);
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /*  Sandbox to handle API call for copy file[end] */

  /*  Sandbox to handle API call for move file[Start] */
  moveFile() {
    this.spinner.show();
    this.driveDataService.moveOption.show = false;
    // Accessing drive API service
    return this.driveApiService.moveFile().subscribe((result: any) => {
      this.driveDataService.driveFileManagement.selectedFileSlug = [];
      this.getFileList();
      this.spinner.hide();
      this.toastService.Success(result.data.message);
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /*  Sandbox to handle API call for move file[end] */

  /*  Sandbox to handle API call for shareFile file[Start] */
  shareFile() {
    this.spinner.show();
    // Accessing drive API service
    return this.driveApiService.shareFile().subscribe((result: any) => {
      this.driveDataService.shareOption.show = false;
      this.driveDataService.driveFileManagement.selectedFileSlug = [];
      this.getFileList();
      this.spinner.hide();
      this.toastService.Success(result.data.message);
    },
      err => {
        this.toastService.Error('Resource already shared to that person!');
        console.log(err);
        this.spinner.hide();
      })
  }
  /*  Sandbox to handle API call for shareFile file[end] */

  /*  Sandbox to handle API call for rename file[start] */
  public renameFile() {
    this.spinner.show();
    // Accessing drive API service
    return this.driveApiService.renameFile().subscribe((result: any) => {
      this.driveDataService.renameFile.show = false;
      this.driveDataService.driveFileManagement.newFileName = '';
      this.driveDataService.driveFileManagement.selectedFileSlug = [];
      this.getFileList();
      this.spinner.hide();
      this.toastService.Success(result.data.message);
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /*  Sandbox to handle API call for rename file[End] */

  copyToClipboard(text){
    var result = this.copyTextToClipboard(text);
    if (result) {
      this.toastService.Success('Copied to Clipboard');
    }
  }

  copyTextToClipboard(text) {
    var txtArea = document.createElement("textarea");
    txtArea.id = 'txt';
    txtArea.style.position = 'fixed';
    txtArea.style.top = '0';
    txtArea.style.left = '0';
    txtArea.style.opacity = '0';
    txtArea.value = text;
    document.body.appendChild(txtArea);
    txtArea.select();
  
    try {
      var successful = document.execCommand('copy');
      var msg = successful ? 'successful' : 'unsuccessful';
      console.log('Copying text command was ' + msg);
      if (successful) {
        return true;
      }
    } catch (err) {
      console.log('Oops, unable to copy');
    } finally {
      document.body.removeChild(txtArea);
    }
    return false;
  }

}
