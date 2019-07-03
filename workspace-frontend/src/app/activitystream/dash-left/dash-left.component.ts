import { Component, OnInit } from '@angular/core';
import { ActStreamDataService } from '../../shared/services/act-stream-data.service';

@Component({
  selector: 'app-dash-left',
  templateUrl: './dash-left.component.html',
  styleUrls: ['./dash-left.component.scss']
})
export class DashLeftComponent implements OnInit {

  constructor(
    public actStreamDataService:ActStreamDataService,
  ) { }

  ngOnInit() {
  }

}
