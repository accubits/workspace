import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TimeAndReportsComponent } from './time-and-reports.component';

describe('TimeAndReportsComponent', () => {
  let component: TimeAndReportsComponent;
  let fixture: ComponentFixture<TimeAndReportsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TimeAndReportsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TimeAndReportsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
