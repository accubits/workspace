import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { DataService } from '../../shared/services/data.service';
import { TaskDataService } from '../../shared/services/task-data.service';
import { FormsSandbox } from './../forms.sandbox';
import { ToastService } from './../../shared/services/toast.service';

@Component({
  selector: 'app-share-option',
  templateUrl: './share-option.component.html',
  styleUrls: ['./share-option.component.scss']
})
export class ShareOptionComponent implements OnInit {
  activeRpTab = 'people';
  addpple = 'all';
  showMore = false;
  searchText = '';

  public assetUrl = Configs.assetBaseUrl;
  constructor(
    public dataService: DataService,
    private formsSandbox: FormsSandbox,
    public taskDataService: TaskDataService,
    private toastService: ToastService
  ) { }

  ngOnInit() {
    for (let i = 0; i < this.dataService.formShare.sharedUsers.length; i++) {
      let selUserinList = this.taskDataService.responsiblePersons.list.filter(
        user => user.slug === this.dataService.formShare.sharedUsers[i].userSlug)[0];
      let idx = this.taskDataService.responsiblePersons.list.indexOf(selUserinList)
      this.taskDataService.responsiblePersons.list[idx]['existing'] = true;
    }
    for (let i = 0; i < this.dataService.formShare.sharedUsers.length; i++) {
      this.dataService.sharedUsers.sharedUserList.push({
        "shareId": this.dataService.formShare.sharedUsers[i].shareId,
        "userSlug": this.dataService.formShare.sharedUsers[i].userSlug,
        "userName": this.dataService.formShare.sharedUsers[i].userName,
        "permission": this.dataService.formShare.sharedUsers[i].permission,
      });
    }

  }

  /* Handling search and listing of user [start] */
  initOrChangeUserList(): void {
    this.searchText ? this.addpple = 'all' : this.addpple = 'search';
    this.taskDataService.responsiblePersons.searchText = this.searchText;
    this.formsSandbox.getUserList();
  }
  /* Handling search and listing of user[end] */


  /* Selecting user[Start] */
  selectUser(selUser): void {
    // Checking if the participant already selected
    let existingUser = this.dataService.sharedUsers.sharedUserList.filter(
      user => user.userSlug === selUser.slug)[0];

    if (existingUser) {
      // toast to handle already added participant
      return;
    }
    this.dataService.sharedUsers.sharedUserList.push({
      "shareId": null,
      "userSlug": selUser.slug,
      "userName": selUser.employee_name,
      "permission": "view",
    });

    selUser.existing = true;
  }
  /* Selecting user[End] */

  /* Removing user[Start] */
  removeUSer(selUser): void {
    // Checking if the participant already selected
    let selUserItem = this.dataService.sharedUsers.sharedUserList.filter(
      user => user.userSlug === selUser.userSlug)[0];

    if (selUserItem) {
      this.dataService.sharedUsers.sharedUserList.splice(this.dataService.sharedUsers.sharedUserList.indexOf(selUserItem), 1);

      // inserting selected user back to user list
      let selUserinList = this.taskDataService.responsiblePersons.list.filter(
        user => user.slug === selUser.userSlug)[0];
      let idx = this.taskDataService.responsiblePersons.list.indexOf(selUserinList)
      this.taskDataService.responsiblePersons.list[idx].existing = false
      return;
    }

  }
  /* Removing user[End] */

  /*Sharing Form[Start] */
  shareForm(): void {
    this.formsSandbox.shareForm();
  }
  /*Sharing Form[End] */

  closeShareOption(): void {
    this.dataService.shareOption.show = false
    this.dataService.resetFormshare();
    this.taskDataService.responsiblePersons.searchText = ''
    this.formsSandbox.getUserList();
  }

  ngOnDestroy() {
    this.dataService.resetFormshare();
    this.taskDataService.responsiblePersons.searchText = ''
    this.formsSandbox.getUserList();
  }
}
