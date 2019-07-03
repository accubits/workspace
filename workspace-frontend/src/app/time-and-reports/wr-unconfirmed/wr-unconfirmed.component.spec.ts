import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { WrUnconfirmedComponent } from './wr-unconfirmed.component';

describe('WrUnconfirmedComponent', () => {
  let component: WrUnconfirmedComponent;
  let fixture: ComponentFixture<WrUnconfirmedComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ WrUnconfirmedComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(WrUnconfirmedComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
