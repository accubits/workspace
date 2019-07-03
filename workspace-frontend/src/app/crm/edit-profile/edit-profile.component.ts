import { SharedModule } from './../../shared/shared.module';
import { CrmDataService } from './../../shared/services/crm-data.service';
import { Component, OnInit } from '@angular/core';


@Component({
  selector: 'app-edit-profile',
  templateUrl: './edit-profile.component.html',
  styleUrls: ['./edit-profile.component.scss']
})
export class EditProfileComponent implements OnInit {

  constructor(
    public crmDataService: CrmDataService,
  ) { }
  process = false;
  status = false;
  reporting = false;
  ngOnInit() {
  }
  showProcess() {
    this.process = !this.process;
  }
  hideProcess() {
    this.process = false;
  }
  showStatus() {
    this.status = !this.status;
  }
  hideStatus() {
    this.status = false;
  }
  showReporting() {
    this.reporting = !this.reporting;
  }
 hideReporting() {
    this.reporting = false;
  }
  hideEditPop() {
    this.crmDataService.editProfile.show = false;
  }
}
