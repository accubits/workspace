import { Component, OnInit } from '@angular/core';
import { DriveDataService } from '../../shared/services/drive-data.service';
import { DriveSandbox } from '../drive.sandbox';
import { Configs } from '../../config';
import { ToastService } from '../../shared/services/toast.service';

@Component({
  selector: 'app-driv-container',
  templateUrl: './driv-container.component.html',
  styleUrls: ['./driv-container.component.scss']
})
export class DrivContainerComponent implements OnInit {
  public assetUrl = Configs.assetBaseUrl;
  constructor(
    public driveDataService: DriveDataService,
    private driveSandbox: DriveSandbox,
    private toastService: ToastService) { }

  ngOnInit() {
    this.driveDataService.getDriveFiles.driveType = [];
    this.driveSandbox.getDriveTypeList();
  }

  /* call function for upload file[start]*/
  fileUploader(files) {
    if ((files[0].size / (1024 * 1024)) > 5) {
      this.toastService.Error("File size can't be grater than 5 MB");
    }
    else {
      let extension = files[0].name.split('.')[files[0].name.split('.').length - 1];
      let selExt = this.driveDataService.getDriveFiles.extensions.indexOf(extension);
      console.log(selExt)      
      if (selExt !== -1) {
        this.toastService.Error("Invalid file format");
        this.driveDataService.uploadOptPopUp.show = false;
         this.driveDataService.fileUpload.show = false;
      }
      else {
        for (var i = 0; i < files.length; i++) {
          this.driveDataService.driveFileManagement.uploadFiles.push(files[i]);
        }
        this.driveDataService.driveFileManagement.sortOption = 'modified';
        this.driveDataService.driveFileManagement.sortMethod = 'desc';
        this.driveSandbox.uploadFile();
      }
    }
  }
  /* call function for upload file[end]*/

  /* call PopUp for create folder[start]*/
  createFolder() {
    this.driveDataService.createFolderPopUp.show = true;
    this.driveDataService.uploadOptPopUp.show = false;
  }
  /* call PopUp for create folder[end]*/

  /* call function for delete file[start] */
  deleteFile() {
    if (this.driveDataService.driveFileManagement.trashAction !== '') {
      this.driveSandbox.deleteTrashFile();
    }
    else {
      this.driveSandbox.deleteFile();
    }
    this.driveDataService.deletePopUp.show = false;
    this.driveDataService.driveFileManagement.selectedFileSlug = [];
  }
  /* call function for delete file[end] */

  /* call function for upload[start] */
  newFile() {
    for (var i = 0; i < this.driveDataService.getDriveFiles.driveFilelist.length; i++) {
      this.driveDataService.getDriveFiles.driveFilelist[i].fileSelect = false;
    }
    this.driveDataService.driveFileManagement.selectAllFooter = false;
    this.driveDataService.driveFileManagement.selectAllHeader = false;
    this.driveDataService.driveFileManagement.selectedFileSlug = [];
    this.driveDataService.resetDrivePopUps();
    this.driveDataService.uploadOptPopUp.show = !this.driveDataService.uploadOptPopUp.show;
  }
  /* call function for upload[end] */
}
