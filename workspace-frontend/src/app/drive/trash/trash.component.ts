import { Component, OnInit, Input } from '@angular/core';
import { Configs } from '../../config';
import { DriveDataService } from '../../shared/services/drive-data.service';
import { DriveSandbox } from '../drive.sandbox';
import { ActivatedRoute, Router } from '@angular/router';

@Component({
  selector: 'app-trash',
  templateUrl: './trash.component.html',
  styleUrls: ['./trash.component.scss']
})

export class TrashComponent implements OnInit {
  public assetUrl = Configs.assetBaseUrl;
  selectAll: boolean;
  @Input('drive') drive;

  constructor(
    public driveDataService: DriveDataService,
    private driveSandbox: DriveSandbox,
    private route: ActivatedRoute,
    private router: Router) { }

  ngOnInit() {
    this.driveDataService.getDriveFiles.selectedTab = "Trash";
    this.driveDataService.uploadBtn.show = false;
    this.driveDataService.resetDriveFiles();
    this.driveDataService.resetDrivePopUps();
    this.route.queryParams.subscribe(params => {
      this.driveDataService.getDriveFiles.selectedDriveSlug = params['slug'];
      this.router.navigate([], { queryParams: { slug: this.driveDataService.getDriveFiles.selectedDriveSlug } });
      this.driveSandbox.getFileList();
    });
  }

  /* close search popup[start]*/
  searchClose() {
    this.driveDataService.driveFileManagement.searchText = '';
    this.driveSandbox.getFileList();
  }
  /* close search popup[end]*/

  /* search file[start]*/
  onSearchChange() {
    this.driveSandbox.searchFile();
  }
  /*  search file[end]*/

  /* select all file[start]*/
  selectAllFiles() {
    this.driveDataService.driveFileManagement.showFooter = true;
    if (this.selectAll == true) {
      this.driveDataService.driveFileManagement.selectedFileSlug = [];
      for (var i = 0; i < this.driveDataService.getDriveFiles.driveFilelist.length; i++) {
        this.driveDataService.getDriveFiles.driveFilelist[i].fileSelect = true;
        this.driveDataService.driveFileManagement.selectedFileSlug.push(this.driveDataService.getDriveFiles.driveFilelist[i].slug);
      }
    }
    else {
      this.driveDataService.driveFileManagement.selectedFileSlug = [];
      for (var i = 0; i < this.driveDataService.getDriveFiles.driveFilelist.length; i++) {
        this.driveDataService.getDriveFiles.driveFilelist[i].fileSelect = false;
      }
    }
  }
  /* select All file [end]*/

  /* sort drive file by selected option[start]*/
  sortOperation(sortOption) {
    this.driveDataService.driveFileManagement.sortOption = sortOption,
    this.driveDataService.driveFileManagement.sortMethod === 'asc' ? this.driveDataService.driveFileManagement.sortMethod = 'desc' : this.driveDataService.driveFileManagement.sortMethod = 'asc';
    this.driveSandbox.getFileList();
    this.driveDataService.resetDrivePopUps();
  }
  /* sort drive file by selected option[end]*/

  /* select file [start]*/
  selectedFile(fileSelect, drive) {
    if(this.driveDataService.driveFileManagement.showFooter === false){
      this.driveDataService.driveFileManagement.selectedFileSlug = [];
    }
    if (fileSelect) {
      this.driveDataService.driveFileManagement.showFooter = true;
      this.driveDataService.driveFileManagement.selectedFileSlug.push(drive.slug);
    }
    else {
      let selSlug = this.driveDataService.driveFileManagement.selectedFileSlug.filter(
        file => file === drive.slug)[0];
      let index = this.driveDataService.driveFileManagement.selectedFileSlug.indexOf(selSlug);
      this.driveDataService.driveFileManagement.selectedFileSlug.pop(index, 1);
    }
  }
  /* select file [end]*/

   /* select more options[start] */
   hideSelectedOption(drive)
   {
    !drive.moreOptionShow;
   }
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

  /* call trashOperation function[start] */
  trashOperation(trashOperation) {
    if (trashOperation === 'delete') {
      this.driveDataService.driveFileManagement.popUpMsg = 'This file/folder will be deleted permanently, do you still want to delete?';
    }
    else {
      this.driveDataService.driveFileManagement.popUpMsg = 'Are you sure you want to restore selected file?';
    }
    this.driveDataService.driveFileManagement.trashAction = trashOperation;
    this.driveDataService.deletePopUp.show = true;
    this.selectAll = false;
  }
  /* call trashOperation function[end] */
}
