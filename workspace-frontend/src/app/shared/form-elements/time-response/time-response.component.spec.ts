import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TimeResponseComponent } from './time-response.component';

describe('TimeResponseComponent', () => {
  let component: TimeResponseComponent;
  let fixture: ComponentFixture<TimeResponseComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TimeResponseComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TimeResponseComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
