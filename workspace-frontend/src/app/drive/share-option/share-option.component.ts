import { Component, OnInit, Input } from '@angular/core';
import { Configs } from '../../config';
import { DriveDataService } from '../../shared/services/drive-data.service';
import { DriveSandbox } from '../drive.sandbox';
import { CookieService } from 'ngx-cookie-service';

@Component({
  selector: 'app-share-option',
  templateUrl: './share-option.component.html',
  styleUrls: ['./share-option.component.scss']
})
export class ShareOptionComponent implements OnInit {
  activeRpTab = 'link';
  addpple = 'recent';
  permission = '';
  @Input('user') user;
  fileName: any;
  path: any;
  firstchoice = false;
  showPepMore = false;
  showDepMore = false;
  linkPermission = 'Can View';
  noUser = false;
  noDept = false;
  public assetUrl = Configs.assetBaseUrl;

  constructor(
    public driveDataService: DriveDataService,
    private cookieService: CookieService,
    private driveSandbox: DriveSandbox,
  ) { }

  ngOnInit() {
    this.driveSandbox.getAllDepartment();
    this.driveSandbox.userList();
    if (this.driveDataService.driveFileManagement.selectedFileSlug.length === 1) {
      let selFile = this.driveDataService.getDriveFiles.driveFilelist.filter(
        file => file.slug === this.driveDataService.driveFileManagement.selectedFileSlug[0])[0];
      this.fileName = selFile.fileName
    }
    else {
      this.fileName = 'Selected files'
    }
    this.driveDataService.driveFileManagement.shearedUserList = [];
    this.driveDataService.driveFileManagement.shearedDeptList = [];
    let selFile = this.driveDataService.getDriveFiles.driveFilelist.filter(
      file => file.slug === this.driveDataService.driveFileManagement.selectedFileSlug[0])[0];
    if (!selFile.isFolder) {
      this.path = selFile.downloadPath;
    }
    else {
      this.path = this.assetUrl + 'authorized/drive/' + this.driveDataService.driveFileManagement.link + '?slug=' + this.driveDataService.getDriveFiles.selectedDriveSlug;
    }
    for (let i = 0; i < selFile.members.length; i++) {
      this.driveDataService.driveFileManagement.shearedUserList.push({ userName: selFile.members[i].userName, userSlug: selFile.members[i].userSlug, permissionName: 'view', permissionDisplay: 'Can View', permissionSlug: selFile.members[i].permissionSlug, userImage: selFile.members[i].userImage })
    }
    this.driveDataService.driveFileManagement.loggedUserSlug = this.cookieService.get('userSlug');
    this.driveDataService.driveFileManagement.searchUser = '';
  }

  selectUser() {
    this.activeRpTab = 'people';
    let userList = this.driveDataService.driveFileManagement.userList;
    let sheredList = this.driveDataService.driveFileManagement.shearedUserList;
    for (var i = 0; i < sheredList.length; i++) {
      let userExist = userList.filter(
        user => user.slug === sheredList[i].userSlug)[0];
      userExist.existing = true;
    }
    let creater = this.driveDataService.getDriveFiles.driveFilelist.filter(
      file => file.slug === this.driveDataService.driveFileManagement.selectedFileSlug[0])[0];
    let createrUser = creater.sharedUserSlug;
    let sheredBy = this.driveDataService.driveFileManagement.userList.filter(
      user => user.slug === createrUser)[0];
    sheredBy.existing = true;
  }

  /* add shered user[start]*/
  sheredUser(user) {
    let userExist = this.driveDataService.driveFileManagement.shearedUserList.filter(
      user => user.userSlug === user.slug)[0];
    if (userExist) {
      return;
    }
    else {
      this.driveDataService.driveFileManagement.shearedUserList.push({ permissionSlug: '', userSlug: user.slug, permissionName: "view", permissionDisplay: 'Can View', userName: user.employee_name, userImage: user.employeeImage });
      user.existing = true;
    }
    if (this.driveDataService.driveFileManagement.userList.length === this.driveDataService.driveFileManagement.shearedUserList.length + 1) {
      this.noUser = true;
    }
  }
  /* add shered user[start]*/

  sheredDepartment(dept) {
    this.driveDataService.driveFileManagement.shearedDeptList.push({ permissionSlug: '', departmentSlug: dept.departmentSlug, permissionName: "view", permissionDisplay: 'Can View', departmentName: dept.departmentName });
    dept.existing = true;

    if (this.driveDataService.driveFileManagement.shearedDeptList.length === this.driveDataService.departmentList.list.length) {
      this.noDept = true;
    }
  }

  /* call file sharing function[start]*/
  shareFile() {
    this.driveSandbox.shareFile();
  }
  /* call file sharing function[end]*/

  /* call copy link to clipboard function[start]*/
  destinationFolderListClipboard() {
    this.driveSandbox.copyToClipboard(this.path);
  }

  /* call copy link to clipboard function[end]*/

  /* call search user function[end]*/
  onSearchChange(searchValue: string) {
    this.driveDataService.driveFileManagement.searchUser = searchValue;
    this.driveSandbox.searchUser();
  }
  /* call search user function[end]*/

  /* call delete user function[start]*/
  deleteUser(user) {
    let deleteUser = this.driveDataService.driveFileManagement.shearedUserList.filter(
      deleteUser => deleteUser.userSlug === user.userSlug)[0];
    let index = this.driveDataService.driveFileManagement.shearedUserList.indexOf(deleteUser);
    this.driveDataService.driveFileManagement.shearedUserList.splice(index, 1);
    let existingUser = this.driveDataService.driveFileManagement.userList.filter(
      existingUser => existingUser.slug === user.userSlug)[0];
    existingUser.existing = false;
    this.noUser = false;
  }
  /* call delete user function[end]*/

  deleteDept(dept) {
    let deletedept = this.driveDataService.driveFileManagement.shearedDeptList.filter(
      deletedept => deletedept.departmentSlug === dept.departmentSlug)[0];
    let index = this.driveDataService.driveFileManagement.shearedDeptList.indexOf(deletedept);
    this.driveDataService.driveFileManagement.shearedDeptList.splice(index, 1);
    let existingDept = this.driveDataService.driveFileManagement.userList.filter(
      existingDept => existingDept.departmentSlug === dept.departmentSlug)[0];
    existingDept.existing = false;
    this.noDept = false;
  }
}
