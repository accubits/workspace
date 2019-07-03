import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ActivitystreamComponent } from './activitystream.component';

describe('ActivitystreamComponent', () => {
  let component: ActivitystreamComponent;
  let fixture: ComponentFixture<ActivitystreamComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ActivitystreamComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ActivitystreamComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
