import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { DriveDataService } from '../../shared/services/drive-data.service';
import { DriveSandbox } from '../drive.sandbox';

@Component({
  selector: 'app-copy-option',
  templateUrl: './copy-option.component.html',
  styleUrls: ['./copy-option.component.scss']
})

export class CopyOptionComponent implements OnInit {
  index: any;
  fileName: any;
  public assetUrl = Configs.assetBaseUrl;

  constructor(
    public driveDataService: DriveDataService,
    private driveSandbox: DriveSandbox) { }

  ngOnInit() {
    this.driveDataService.driveFileManagement.popUpHierarchy = [];
    this.driveDataService.driveFileManagement.destinationFolderList = [];
    this.driveDataService.createDestinationFolder.show = false;
    for (let i = 0; i < this.driveDataService.driveFileManagement.hierarchy.length; i++) {
      this.driveDataService.driveFileManagement.popUpHierarchy.push(this.driveDataService.driveFileManagement.hierarchy[i])
    }
    for (let i = 0; i < this.driveDataService.getDriveFiles.driveFilelist.length; i++) {
      this.driveDataService.driveFileManagement.destinationFolderList.push(this.driveDataService.getDriveFiles.driveFilelist[i])
    }
    if(this.driveDataService.driveFileManagement.selectedFileSlug.length === 1){
      let selFile = this.driveDataService.getDriveFiles.driveFilelist.filter(
        file => file.slug === this.driveDataService.driveFileManagement.selectedFileSlug[0])[0];
       this.fileName = selFile.fileName
    }
    else{
      this.fileName = 'Selected files'
    }
    if (this.driveDataService.driveFileManagement.popUpHierarchy.length > 0) {
      let last: any = this.driveDataService.driveFileManagement.popUpHierarchy[this.driveDataService.driveFileManagement.popUpHierarchy.length - 1];
      this.driveDataService.driveFileManagement.lastFolder = last.folderName;
    }
  }

   /* select drive [start]*/
   selectDrive() {
    this.driveDataService.driveFileManagement.destinationSlug = this.driveDataService.getDriveFiles.selectedDriveSlug;
    this.driveDataService.driveFileManagement.popUpHierarchy = [];
    this.driveSandbox.destinationFolderList();
  }
  /* select drive [end]*/

   /* open selected folder from file list[start]*/
   openSelectedFolder(folder, index) {
    let length = this.driveDataService.driveFileManagement.popUpHierarchy.length;
    if (index != length) {
      this.driveDataService.driveFileManagement.popUpHierarchy.splice(index + 1, length);
    }
    this.driveDataService.driveFileManagement.destinationSlug = folder.folderSlug;
    this.driveSandbox.destinationFolderList();
  }
  /* open selected folder from file list[end]*/

  /* open previous folder[start]*/
  openPreviousFolder() {
    let length = this.driveDataService.driveFileManagement.popUpHierarchy.length;
    this.driveDataService.driveFileManagement.popUpHierarchy.pop(length - 1, 1);
    let last: any = this.driveDataService.driveFileManagement.popUpHierarchy[this.driveDataService.driveFileManagement.popUpHierarchy.length - 1];
    this.driveDataService.driveFileManagement.lastFolder = last.folderName;
    this.driveDataService.driveFileManagement.destinationSlug = last.folderSlug;
    this.driveSandbox.destinationFolderList();
  }
  /* open previous folder[end]*/

  /* select destination folder for copy file[start]*/
  selectTargetFolder(destinationFolderList) {
    if (this.driveDataService.driveFileManagement.fileLoading) return;
    this.driveDataService.driveFileManagement.fileLoading = true;
    this.driveDataService.driveFileManagement.destinationSlug = destinationFolderList.slug;
    this.driveDataService.driveFileManagement.lastFolder = destinationFolderList.fileName;
    this.driveDataService.driveFileManagement.popUpHierarchy.push({ folderName: destinationFolderList.fileName, folderSlug: destinationFolderList.slug, path: destinationFolderList.path })
    this.driveSandbox.destinationFolderList();
  }
  /* select destination folder for copy file[end]*/
 
  /* call copy function[start]*/
  copyFile() {
    this.driveSandbox.copyFile();
  }
  /* call copy function[end]*/

  /* create folder[start]*/
  createFolder() {
    this.driveDataService.createDestinationFolder.show = true;
  }
  /* create folder[end]*/

  /* create new folder[start]*/
  creatNewFolder() {
    this.driveDataService.createDestinationFolder.show = false;
    this.driveSandbox.createCopyFolder();
  }
  /* create new folder[end]*/

  /* close copy popup[start]*/
  closePop() {
    this.driveDataService.createDestinationFolder.show = false;
  }
  /* close copy popup[end]*/
}
