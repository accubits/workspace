import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-event-end',
  templateUrl: './event-end.component.html',
  styleUrls: ['./event-end.component.scss']
})
export class EventEndComponent implements OnInit {
  activeRpTab : string = '';
  attend : string = '';
  invite : string = '';
  decline : string = '';

  constructor() { }

  ngOnInit() {
  }

}
