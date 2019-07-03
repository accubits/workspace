import { Component, OnInit, Input } from '@angular/core';
import { Configs } from '../../config';
import { DriveDataService } from '../../shared/services/drive-data.service';
import { DriveSandbox } from '../drive.sandbox';

@Component({
  selector: 'app-drive-table',
  templateUrl: './drive-table.component.html',
  styleUrls: ['./drive-table.component.scss']
})
export class DriveTableComponent implements OnInit {
  public assetUrl = Configs.assetBaseUrl;
  @Input('drive') drive;
  @Input('index') index;
  sheredBy: any;

  constructor(public driveDataService: DriveDataService,
    private driveSandbox: DriveSandbox) { }

  ngOnInit() {
  }

  /* select file [start]*/
  selectedFile(fileSelect, drive) {
    if(this.driveDataService.driveFileManagement.showFooter === false){
      this.driveDataService.driveFileManagement.selectedFileSlug = [];
    }
   if (fileSelect) {
     if(drive.isFolder === true){
      this.driveDataService.driveFileManagement.isFolder = true;
     }
     else{
      this.driveDataService.driveFileManagement.isFolder = false;
     }
      this.driveDataService.driveFileManagement.showFooter = true;
      this.driveDataService.driveFileManagement.selectedFileSlug.push(drive.slug);
      if (this.driveDataService.getDriveFiles.driveFilelist.length === this.driveDataService.driveFileManagement.selectedFileSlug.length) {
        this.driveDataService.driveFileManagement.selectAllHeader = true;
        this.driveDataService.driveFileManagement.selectAllFooter = true;
      }
    }
    else {
      let selSlug = this.driveDataService.driveFileManagement.selectedFileSlug.filter(
        file => file === drive.slug)[0];
      let index = this.driveDataService.driveFileManagement.selectedFileSlug.indexOf(selSlug);
      this.driveDataService.driveFileManagement.selectedFileSlug.splice(index, 1);
      this.driveDataService.renameFile[this.index] = false;
      this.driveDataService.driveFileManagement.selectAllHeader = false;
      this.driveDataService.driveFileManagement.selectAllFooter = false;
    }
  }
  /* select file [end]*/
 
  /* open folder[start] */
  openFileFolder() {
    this.driveDataService.resetDrivePopUps();
    if (this.driveDataService.driveFileManagement.fileLoading) return;
    this.driveDataService.driveFileManagement.fileLoading = true;
    this.driveDataService.driveFileManagement.hierarchy.push({ folderName: this.drive.fileName, folderSlug: this.drive.slug, path: this.drive.path });
    this.driveDataService.driveFileManagement.sourceSlug = this.drive.slug;
    this.driveSandbox.getFileList();
    this.driveDataService.driveFileManagement.selectedFileSlug = [];
  }
  /* open folder[end] */

  /* rename selected file[start] */
  changeFileName() {
    if (this.driveDataService.driveFileManagement.newFileName === '') {
    }
    else {
      this.driveSandbox.renameFile();
      this.driveDataService.renameFile[this.index] = false;
    }
  }
  /* rename selected file[end] */

  /* call shared User list[start] */
  sharedUser(drive) {
    this.driveDataService.driveFileManagement.shearedUserList = drive.members;
    this.driveDataService.userCount[this.index] = !this.driveDataService.userCount[this.index];
  }
  /* call shared User list[end] */

  /* select more options[start] */
  selectedOption(drive) {
    this.driveDataService.driveFileManagement.showFooter = false;
    this.driveDataService.driveFileManagement.selectedFileSlug = [];
    for (var i = 0; i < this.driveDataService.getDriveFiles.driveFilelist.length; i++) {
      this.driveDataService.getDriveFiles.driveFilelist[i].fileSelect = false;
    }
    this.driveDataService.driveFileManagement.selectAllFooter = false;
    this.driveDataService.driveFileManagement.selectAllHeader = false;
    this.driveDataService.driveFileManagement.selectedFileSlug.push(drive.slug);
    drive.moreOptionShow = !drive.moreOptionShow;
  }
  /* select more options[end] */

  /* call file rename function[start] */
  renameOption() {
    let selFile = this.driveDataService.getDriveFiles.driveFilelist.filter(
      file => file.slug === this.driveDataService.driveFileManagement.selectedFileSlug[0])[0];
    let index = this.driveDataService.getDriveFiles.driveFilelist.indexOf(selFile);
    this.driveDataService.renameFile[index] = true;
    this.driveDataService.driveFileManagement.newFileName = selFile.fileName.split(".", 2)[0];;
    
  }
  /* call file rename function[end] */

  /* call file delete function[start] */
  deleteFile() {
    this.driveDataService.driveFileManagement.popUpMsg = 'Are you sure you want to delete selected file?'
    this.driveDataService.deletePopUp.show = true;
  }
  /* call file delete function[end] */

  /* call file dowload function[start] */
  downloadFile() {
    let selFile = this.driveDataService.getDriveFiles.driveFilelist.filter(
      file => file.slug === this.driveDataService.driveFileManagement.selectedFileSlug[0])[0];
      if(selFile){
        this.driveDataService.driveFileManagement.selectedFileSlug = [];
        this.driveDataService.driveFileManagement.selectAllFooter = false;
        this.driveDataService.driveFileManagement.selectAllHeader = false;
        selFile.fileSelect = false;
        window.open(selFile.downloadPath)
      }
  }
  /* call file dowload function[end] */

  /* select all file[start]*/
  selectAllFiles() {
    this.driveDataService.resetDrivePopUps();
    if (this.driveDataService.driveFileManagement.selectAllFooter) {
      this.driveDataService.driveFileManagement.selectedFileSlug = [];
      for (var i = 0; i < this.driveDataService.getDriveFiles.driveFilelist.length; i++) {
        this.driveDataService.getDriveFiles.driveFilelist[i].fileSelect = true;
        this.driveDataService.driveFileManagement.selectedFileSlug.push(this.driveDataService.getDriveFiles.driveFilelist[i].slug);
      }
      this.driveDataService.driveFileManagement.selectAllHeader = true;
    }
    else {
      this.driveDataService.driveFileManagement.showFooter = false;
      this.driveDataService.driveFileManagement.selectedFileSlug = [];
      for (var i = 0; i < this.driveDataService.getDriveFiles.driveFilelist.length; i++) {
        this.driveDataService.getDriveFiles.driveFilelist[i].fileSelect = false;
      }
      this.driveDataService.driveFileManagement.selectAllHeader = false;
    }
  }
  /* select all file[end]*/
}

