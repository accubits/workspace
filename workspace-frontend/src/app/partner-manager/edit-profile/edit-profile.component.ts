import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { SettingsDataService } from '../../shared/services/settings-data.service';
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
import { PartnerManagerSandbox } from '../partner-manager.sandbox'
import { PartnerSandbox} from  '../../partner/partner.sandbox'

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

  constructor(
    public settingsDataService: SettingsDataService,
    public partnerManagerSandbox: PartnerManagerSandbox,
    private spinner: Ng4LoadingSpinnerService
  ) { }

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

  /*Set default image[Start]*/

  setImage(event){
    this.image = this.assetUrl+'assets/images/all/tdp1.png'
    this.hideRemove = true;
  }
    /*Set default image[End]*/

  /* Remove Uploaded File [Start] */
  removeProfile(): void {
    this.spinner.show();
    this.settingsDataService.editSettingsTemplate.imageUrl = '';
    this.image = '';
    this.settingsDataService.editProfile.file[0]=null;
    this.partnerManagerSandbox.removeProfile();
  }
  /* Remove Uploaded File [End] */

  /* Edit Profile [Start] */

    editProfile(): void {
    this.spinner.show();
     this.partnerManagerSandbox.editPartnerProfile();
  }
    /* Edit Profile [End] */

    


 

}
