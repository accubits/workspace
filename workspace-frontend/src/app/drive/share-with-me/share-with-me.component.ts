import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { DriveDataService } from '../../shared/services/drive-data.service';
import { DriveSandbox } from '../drive.sandbox';
import { ActivatedRoute, Router } from '@angular/router';

@Component({
  selector: 'app-share-with-me',
  templateUrl: './share-with-me.component.html',
  styleUrls: ['./share-with-me.component.scss']
})
export class ShareWithMeComponent implements OnInit {
  public assetUrl = Configs.assetBaseUrl;

  constructor(
    public driveDataService: DriveDataService,
    private driveSandbox: DriveSandbox,
    private route: ActivatedRoute,
    private router: Router) { }

  ngOnInit() {
    this.driveDataService.getDriveFiles.selectedTab = "Shared With Me";
    this.driveDataService.driveFileManagement.link = 'share-with-me';
    this.driveDataService.uploadBtn.show = false;
    this.driveDataService.resetDriveFiles();
    this.driveDataService.resetDrivePopUps();
    this.route.queryParams.subscribe(params => {
      this.driveDataService.getDriveFiles.selectedDriveSlug = params['slug'];
      this.router.navigate([], { queryParams: { slug: this.driveDataService.getDriveFiles.selectedDriveSlug } });
      this.driveSandbox.getFileList();
    });
  }

  /* select drive from breadcrumb [start]*/
  selectDrive() {
    this.driveDataService.resetDriveFiles();
    this.driveSandbox.getFileList();
  }
  /* select drive from breadcrumb [end]*/

  /* open selected folder from breadcrumb[start]*/
  selectHierarchy(folder) {
    let length = this.driveDataService.driveFileManagement.hierarchy.length;
    let selFolder = this.driveDataService.driveFileManagement.hierarchy.filter(
      file => file.folderSlug === folder.folderSlug)[0];
    let index = this.driveDataService.driveFileManagement.hierarchy.indexOf(selFolder);
    if (index != length) {
      this.driveDataService.driveFileManagement.hierarchy.splice(index + 1, length);
    }
    this.driveDataService.driveFileManagement.sourceSlug = folder.folderSlug;
    this.driveSandbox.getFileList();
  }
  /* open selected folder from breadcrumb[end]*/

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

  /* select All file [start]*/
  selectAll() {
    if (this.driveDataService.driveFileManagement.selectAllHeader) {
      this.driveDataService.resetDrivePopUps();
      this.driveDataService.driveFileManagement.showFooter = true;
      this.driveDataService.driveFileManagement.selectedFileSlug = [];
      for (var i = 0; i < this.driveDataService.getDriveFiles.driveFilelist.length; i++) {
        this.driveDataService.driveFileManagement.selectedFileSlug.push({ slug: this.driveDataService.getDriveFiles.driveFilelist[i].slug });
        this.driveDataService.getDriveFiles.driveFilelist[i].fileSelect = true;
      }
      this.driveDataService.driveFileManagement.selectAllFooter = true;
    }
    else {
      this.driveDataService.driveFileManagement.showFooter = false;
      this.driveDataService.driveFileManagement.selectedFileSlug = [];
      for (var i = 0; i < this.driveDataService.getDriveFiles.driveFilelist.length; i++) {
        this.driveDataService.getDriveFiles.driveFilelist[i].fileSelect = false;
      }
      this.driveDataService.driveFileManagement.selectAllFooter = false;
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

  /* create new folder[start]*/
  creatNewFolder() {
    this.driveDataService.createFolderPopUp.show = false;
    this.driveSandbox.createFolder();
  }
  /* create new folder[end]*/

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
}
