import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ActivityStreamPanelComponent } from './activity-stream-panel.component';

describe('ActivityStreamPanelComponent', () => {
  let component: ActivityStreamPanelComponent;
  let fixture: ComponentFixture<ActivityStreamPanelComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ActivityStreamPanelComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ActivityStreamPanelComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
