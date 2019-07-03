import { Component, OnInit } from '@angular/core';
import { DataService } from '../../shared/services/data.service';
import { TaskDataService } from '../../shared/services/task-data.service';
import { FormsSandbox } from './../forms.sandbox';
import { ToastService } from './../../shared/services/toast.service';
import { Location } from '@angular/common';
import { Configs } from '../../config';

@Component({
  selector: 'app-publish-form',
  templateUrl: './publish-form.component.html',
  styleUrls: ['./publish-form.component.scss']
})
export class PublishFormComponent implements OnInit {
  activeRpTab = 'link';
  addpple = 'recent';
  activepubTab = 'pubAll';
  searchText = ''
  selectedUsers = [];
  formSubmitLoader = '';
  public frntEndUrl =  '' ;
  userlistingdrop =  false;
  constructor(
    public dataService: DataService,
    public taskDataService: TaskDataService,
    private formsSandbox: FormsSandbox,
    private toastService: ToastService,
    private location: Location,
  ) { }

  ngOnInit() {
    this.frntEndUrl = 'http://52.220.41.10/workspace-frontend/dist/';
    this.formSubmitLoader = this.frntEndUrl + 'authorized/forms/form_submit/' + this.dataService.viewForm.selctedFormSlug;
    for (let i = 0; i < this.dataService.formSend.sendUsers.length; i++) {
      let selUserinList = this.taskDataService.responsiblePersons.list.filter(
        user => user.slug === this.dataService.formSend.sendUsers[i].userSlug)[0];
      let idx = this.taskDataService.responsiblePersons.list.indexOf(selUserinList)
      this.taskDataService.responsiblePersons.list[idx]['existing'] = true;
    }
  }

  /* Handling search and listing of user [start] */
  initOrChangeUserList(): void {
    this.userlistingdrop =  true;
    // this.searchText ? this.activepubTab = 'pubSearch' : this.activepubTab = 'pubAll';
    this.taskDataService.responsiblePersons.searchText = this.searchText;
    this.formsSandbox.getUserList();
  }
  /* Handling search and listing of res user[end] */

    /* Selecting user[Start] */
    selectUser(selUser): void {
      // Checking if the participant already selected
      let existingUser = this.dataService.formSend.sendUsers.filter(
        user => user.userSlug === selUser.slug)[0];
  
      if (existingUser) {
        // toast to handle already added participant
        return;
      }
  
      this.dataService.formSend.sendUsers.push({
        "sendId": null,
        "userSlug": selUser.slug,
        "userName": selUser.employee_name,
        "imageUrl": selUser.employeeImage

      });
       selUser.existing = true;
    }
    /* Selecting user[End] */

    /* Removing user[Start] */
    removeUSer(index): void {
         // inserting selected user back to user list
         let selUserinList = this.taskDataService.responsiblePersons.list.filter(
          user => user.slug === this.dataService.formSend.sendUsers[index].userSlug)[0];
        let idx = this.taskDataService.responsiblePersons.list.indexOf(selUserinList)
        this.taskDataService.responsiblePersons.list[idx].existing = false
       
        this.dataService.formSend.sendUsers.splice(index,1);
    }
    /* Removing user[End] */
  
   /*Sharing Form[Start] */
   SendForm():void{
     if(this.dataService.formSend.sendUsers.length === 0 ){
       this.toastService.Error("",'User list empty');
       return;
     }

     this.dataService.sendUsers.sendUserList = this.dataService.formSend.sendUsers
     this.dataService.sendUsers.option = 'publish'
     this.formsSandbox.sendForm();
   }
   /*Sharing Form[End] */

   /* cancel Share */
   cancelFormSend():void{
    this.dataService.publish_form.show = false
    this.location.back(); 
    this.dataService.resetFormSend();
   }

   closeUserList():void{
     this.userlistingdrop =  false;
     this.searchText = '';
     this.taskDataService.responsiblePersons.searchText = this.searchText;
     this.formsSandbox.getUserList();
   }

   toogleUserList():void{
     this.userlistingdrop = !this.userlistingdrop;
     if(!this.userlistingdrop){
      this.searchText = '';
      this.taskDataService.responsiblePersons.searchText = this.searchText;
      this.formsSandbox.getUserList();
     }
   }

}
