import { Component, OnInit } from '@angular/core';
import { HrmDataService} from './../../shared/services/hrm-data.service';
import { HrmSandboxService } from '../hrm.sandbox';
import { UtilityService } from '../../shared/services/utility.service';

@Component({
  selector: 'app-absence-detail',
  templateUrl: './absence-detail.component.html',
  styleUrls: ['./absence-detail.component.scss']
})
export class AbsenceDetailComponent implements OnInit {

  constructor(
    public hrmDataService: HrmDataService,
    public hrmSandboxService: HrmSandboxService,
    private utilityService: UtilityService
  ) { }

  ngOnInit() {
  }
  hideAbsenceDetail() {
    this.hrmDataService.absenceDetail.show = false;
  }
  editAbsence(){
    this.hrmDataService.absent.action = 'update';
    this.hrmDataService.toUsers.toUsers.push({'name': this.hrmDataService.absentDetails.absentUserName, 'slug': this.hrmDataService.absentDetails.absentUser});
    this.hrmDataService.absent.absentStartsOn = new Date(this.hrmDataService.absentDetails.absentStartsOn * 1000);
    this.hrmDataService.absent.absentEndsOn = new Date(this.hrmDataService.absentDetails.absentEndsOn  * 1000);
    this.hrmDataService.absent.type.push({'name': this.hrmDataService.absentDetails.leaveType});
    this.hrmDataService.absent.reason = this.hrmDataService.absentDetails.reason;
    this.hrmDataService.newAbsence.show = true;
  }
  deleteAbscent(){
    this.hrmDataService.deletePopUp.show = false;
    this.hrmDataService.deleteMessage.msg = 'Are you sure you want to delete selected Abscent Details?';
    this.hrmDataService.deletePopUp.show = true;
  }

  conformDelete(){
    this.hrmDataService.absent.action = 'remove';
    this.hrmSandboxService.createAbsence();
  }
}
