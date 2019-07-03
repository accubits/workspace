import { Component, OnInit } from '@angular/core';
import { HrmDataService } from './../../shared/services/hrm-data.service';

@Component({
  selector: 'app-inform-detail',
  templateUrl: './inform-detail.component.html',
  styleUrls: ['./inform-detail.component.scss']
})
export class InformDetailComponent implements OnInit {

  constructor(
    public hrmDataService: HrmDataService) { }

  ngOnInit() {
  }

}
