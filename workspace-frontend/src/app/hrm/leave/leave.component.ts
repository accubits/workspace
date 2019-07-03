
import { Component, OnInit } from '@angular/core';
import { HrmDataService} from '../../shared/services/hrm-data.service';

@Component({
  selector: 'app-leave',
  templateUrl: './leave.component.html',
  styleUrls: ['./leave.component.scss']
})
export class LeaveComponent implements OnInit {
  //showDetailPopup: boolean = false;
  constructor(
    public hrmDataService: HrmDataService
  ) { }

  ngOnInit() {
  }
}
