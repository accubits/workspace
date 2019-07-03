import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DrUnconfirmedComponent } from './dr-unconfirmed.component';

describe('DrUnconfirmedComponent', () => {
  let component: DrUnconfirmedComponent;
  let fixture: ComponentFixture<DrUnconfirmedComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DrUnconfirmedComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DrUnconfirmedComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
