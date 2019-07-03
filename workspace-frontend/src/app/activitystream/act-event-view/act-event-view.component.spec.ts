import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ActEventViewComponent } from './act-event-view.component';

describe('ActEventViewComponent', () => {
  let component: ActEventViewComponent;
  let fixture: ComponentFixture<ActEventViewComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ActEventViewComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ActEventViewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
