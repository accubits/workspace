import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { SettingsDataService } from '../../shared/services/settings-data.service';
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
import { SettingsSandbox } from '../settings.sandbox';

@Component({
  selector: 'app-edit-profile',
  templateUrl: './edit-profile.component.html',
  styleUrls: ['./edit-profile.component.scss']
})

export class EditProfileComponent implements OnInit {
  image: string;
  department: boolean =  false;
  public assetUrl = Configs.assetBaseUrl;
  interestValue = "";
  error: string;
  newInterest:any;
  hideRemove=false;




  /* Preparing Owl Date[Start] */
  date: Date = new Date();
  settings = {
    bigBanner: true,
    timePicker: true,
    format: 'dd-MM-yyyy',
    defaultOpen: false,
    hour12Timer: true
  };
  /* Preparing Owl Date[End] */

  public todayDate: any = new Date();
  constructor(
    public settingsDataService: SettingsDataService,
    public settingsSandbox: SettingsSandbox,
    private spinner: Ng4LoadingSpinnerService
  ) { }
  departList: boolean = false;
  interestList: boolean = false;
  reportingTo: boolean = false;
  ngOnInit() {
    // Initial executions

    this.settingsDataService.editProfile.interests=[];
    for (let i = 0; i < this.settingsDataService.editSettingsTemplate.interest.length; i++) {
      console.log(this.settingsDataService.editSettingsTemplate.interest[i].title)
      let obj = {
        "slug": this.settingsDataService.editSettingsTemplate.interest[i].slug,
        "interest": this.settingsDataService.editSettingsTemplate.interest[i].title,
        "action": "edit"
      }
      this.settingsDataService.editProfile.interests.push(obj)
    }
    this.image = this.settingsDataService.editSettingsTemplate.imageUrl

  }
  departShow() {
    this.departList = true;
  }
  departHide() {
    this.departList = false;
  }
  showIntrst() {
    this.interestList = true;
  }
  hideIntrst() {
    this.interestList = false;
  }
  reportingPersonHide() {
    this.reportingTo = false;
  }
  reportingPersonShow() {
    this.reportingTo = true;
  }

  /* File upload[Start] */

  uploadFile(files) {
    if (files && files[0]) {
       this.settingsDataService.editProfile.file[0] = files[0]
      var reader = new FileReader();
      reader.onload = (event: ProgressEvent) => {
        this.image = (<FileReader>event.target).result;
      }
      reader.readAsDataURL(files[0]);
    }
    this.hideRemove = false;

  }

  /* File upload[End] */

  /* Remove Uploaded File [Start] */

  removeProfile(): void {
    this.spinner.show();
    this.settingsDataService.editSettingsTemplate.imageUrl = '';
    this.image = '';
    this.settingsDataService.editProfile.file[0]=null;
    this.settingsSandbox.removeProfile();

  }
  /* Remove Uploaded File [End] */

  /* Edit Profile [Start] */

    editProfile(): void {
    this.spinner.show();

    this.settingsSandbox.editProfile();
  }
    /* Edit Profile [End] */

    /* Add  Interests[Start] */

    addInterest(event) {

        this.newInterest = this.settingsDataService.editProfile.interests.filter(
        interest => interest.interest === event.target.value) [0]
        if(this.newInterest)
        return
        console.log(this.newInterest);

     if (event.keyCode === 13) {
     // console.log(event.target.value, 'text value');
      let obj = {
        "slug": "",
        "interest": event.target.value,
        "action": "create"
      }
      this.settingsDataService.editProfile.interests.push(obj);
      event.currentTarget.value = '';
    }
  }
  /* Add  Interests[End] */

    /*Set default image[Start]*/

  setImage(){
    this.image = this.assetUrl+'assets/images/all/tdp1.png'
    this.hideRemove = true;

  }
      /*Set default image[End]*/

  /* Delete  Interests[Start] */
    deleteInterest(i) {
    this.settingsDataService.editProfile.interests.splice(i, 1);
  }
    /* Delete  Interests[End] */

    makeEditable(index):void{
      this.settingsDataService.editProfile.interests[index].editable =  true;

    }
}
