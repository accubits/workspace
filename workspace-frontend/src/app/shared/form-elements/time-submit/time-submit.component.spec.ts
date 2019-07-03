import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TimeSubmitComponent } from './time-submit.component';

describe('TimeSubmitComponent', () => {
  let component: TimeSubmitComponent;
  let fixture: ComponentFixture<TimeSubmitComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TimeSubmitComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TimeSubmitComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
