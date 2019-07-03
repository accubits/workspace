import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ExpiredDetailTabComponent } from './expired-detail-tab.component';

describe('ExpiredDetailTabComponent', () => {
  let component: ExpiredDetailTabComponent;
  let fixture: ComponentFixture<ExpiredDetailTabComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ExpiredDetailTabComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ExpiredDetailTabComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
