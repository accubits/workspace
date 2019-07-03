import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { DataService } from '../../shared/services/data.service';
import { FormsSandbox } from './../forms.sandbox';
import { TaskDataService } from '../../shared/services/task-data.service';
import { ToastService } from './../../shared/services/toast.service';

@Component({
  selector: 'app-send-to',
  templateUrl: './send-to.component.html',
  styleUrls: ['./send-to.component.scss']
})
export class SendToComponent implements OnInit {
  activeRpTab = 'people';
  addpple = 'all';
  showMore = false;
  searchText = '';

  public assetUrl = Configs.assetBaseUrl;

  constructor(
    public dataService: DataService,
    private formsSandbox: FormsSandbox,
    private toastService: ToastService,
    public taskDataService: TaskDataService,


  ) { }

  ngOnInit() {
    console.log('form send',this.dataService.formSend.sendUsers)
    for (let i = 0; i < this.dataService.formSend.sendUsers.length; i++) {
      let selUserinList = this.taskDataService.responsiblePersons.list.filter(
        user => user.slug === this.dataService.formSend.sendUsers[i].userSlug)[0];
      let idx = this.taskDataService.responsiblePersons.list.indexOf(selUserinList)
      this.taskDataService.responsiblePersons.list[idx]['existing'] = true;
    }
    for (let i = 0; i < this.dataService.formSend.sendUsers.length; i++) {
      this.dataService.sendUsers.sendUserList.push({
        "sendId": this.dataService.formSend.sendUsers[i].sendId,
        "userSlug": this.dataService.formSend.sendUsers[i].userSlug,
        "userName": this.dataService.formSend.sendUsers[i].userName,
        "permission": this.dataService.formSend.sendUsers[i].permission,
      });
    }

    console.log('jhfkjdkjkjd',this.dataService.sendUsers.sendUserList)
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
    let existingUser = this.dataService.sendUsers.sendUserList.filter(
      user => user.userSlug === selUser.slug)[0];

    if (existingUser) {
      // toast to handle already added participant
      return;
    }
    this.dataService.sendUsers.sendUserList.push({
      "sendId": null,
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
    let selUserItem = this.dataService.sendUsers.sendUserList.filter(
      user => user.userSlug === selUser.userSlug)[0];

    if (selUserItem) {
      this.dataService.sendUsers.sendUserList.splice(this.dataService.sendUsers.sendUserList.indexOf(selUserItem), 1);

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
  sendForm(): void {
    this.dataService.formSend.formSlug = this.dataService.formShare.formSlug;
    this.formsSandbox.sendForm();
  }
  /*Sharing Form[End] */
  closeShareOption(): void {
    this.dataService.sendToOption.show = false
    this.dataService.formSend.sendUsers = [];
    this.dataService.sendUsers.sendUserList = [];
    this.dataService.resetFormSend();
    this.taskDataService.responsiblePersons.searchText = ''
    this.formsSandbox.getUserList();
  }

  ngOnDestroy() {
    this.dataService.resetFormSend();
    this.taskDataService.responsiblePersons.searchText = ''
    this.formsSandbox.getUserList();
  }
}
